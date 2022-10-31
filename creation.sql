-----------------------------------------
-- Drop old schema
-----------------------------------------

DROP SCHEMA IF EXISTS lbaw2252 CASCADE;
CREATE SCHEMA lbaw2252;
SET search_path TO lbaw2252;

CREATE TYPE NotificationTypes AS ENUM ('EventChange','JoinRequestReviewed','OrganizerRequestReviewed','InviteReceived','InviteAccepted','NewPoll','NewInvitation');
CREATE TYPE AccountStatus AS ENUM ('Active','Disabled','Blocked');
CREATE TYPE UserTypes AS ENUM ('User','Organizer','Admin');

CREATE TABLE Users(
  userId SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL CONSTRAINT unique_usernam_uk UNIQUE,
  name VARCHAR(150) NOT NULL, 
  email TEXT NOT NULL CONSTRAINT user_email_uk UNIQUE,
  password TEXT NOT NULL,
  userPhoto TEXT,
  accountStatus AccountStatus NOT NULL,
  userType UserTypes NOT NULL
);

CREATE TABLE Event(
    eventId SERIAL PRIMARY KEY,
    userId INTEGER NOT NULL REFERENCES Users (userId) ON UPDATE CASCADE,
    eventName TEXT NOT NULL CONSTRAINT unique_eventName UNIQUE,
    public BOOLEAN NOT NULL,
    address TEXT NOT NULL,
    description TEXT NOT NULL,
    eventCanceled BOOLEAN NOT NULL DEFAULT TRUE,
    eventPhoto TEXT NOT NULL,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    CONSTRAINT end_after_start_ck CHECK (endDate > startDate)
);

CREATE TABLE Attendee(
  attendeeId INTEGER NOT NULL REFERENCES Users (userId) ON UPDATE CASCADE,
  eventId INTEGER NOT NULL REFERENCES Event (eventId) ON UPDATE CASCADE,
  PRIMARY KEY(attendeeId, eventId)
);

CREATE TABLE Category(
  categoryId SERIAL PRIMARY KEY,
  categoryName TEXT NOT NULL CONSTRAINT category_uk UNIQUE
);

CREATE TABLE Tag(
  tagId SERIAL PRIMARY KEY,
  tagName TEXT NOT NULL CONSTRAINT tag_uk UNIQUE
);

CREATE TABLE Report(
  reportId SERIAL PRIMARY KEY,
  reporterId INTEGER NOT NULL REFERENCES Users (userId) ON UPDATE CASCADE,
  eventId INTEGER NOT NULL REFERENCES Event (eventId) ON UPDATE CASCADE,
  message TEXT NOT NULL,
  reportStatus BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE Invitation(
  invitationId SERIAL PRIMARY KEY,
  inviterId INTEGER NOT NULL REFERENCES Users (userId) ON UPDATE CASCADE,
  inviteeId INTEGER NOT NULL REFERENCES Users (userId) ON UPDATE CASCADE,
  eventId INTEGER NOT NULL REFERENCES Event (eventId) ON UPDATE CASCADE,
  invitationStatus BOOLEAN,
  CONSTRAINT invite_To_Self_ck CHECK (inviterId != inviteeId)
);

CREATE TABLE Poll(
  pollId SERIAL PRIMARY KEY,
  eventId INTEGER NOT NULL REFERENCES Event (eventId) ON UPDATE CASCADE,
  pollContent TEXT NOT NULL
);

CREATE TABLE Comment(
  commentId SERIAL PRIMARY KEY,
  authorId INTEGER NOT NULL REFERENCES Users (userId) ON UPDATE CASCADE,
  eventId INTEGER NOT NULL REFERENCES Event (eventId) ON UPDATE CASCADE,
  commentContent TEXT NOT NULL,
  commentDate DATE NOT NULL
);

CREATE TABLE JoinRequest(
  JoinRequestId SERIAL PRIMARY KEY,
  requesterId INTEGER NOT NULL REFERENCES Users (userId) ON UPDATE CASCADE,
  eventId INTEGER NOT NULL REFERENCES Event (eventId) ON UPDATE CASCADE,
  requestStatus BOOLEAN
);

CREATE TABLE OrganizerRequest(
  OrganizerRequestId SERIAL PRIMARY KEY,
  requesterId INTEGER NOT NULL REFERENCES Users (userId) ON UPDATE CASCADE,
  requestStatus BOOLEAN
);

CREATE TABLE Notification(
  notificationId SERIAL PRIMARY KEY,
  receiverId INTEGER NOT NULL REFERENCES Users (userId),
  eventId INTEGER REFERENCES Event (eventId),
  joinRequestId INTEGER REFERENCES JoinRequest (joinRequestId),
  organizerRequestId INTEGER REFERENCES OrganizerRequest (organizerRequestId),
  invitationId INTEGER REFERENCES Invitation (invitationId),
  pollId INTEGER REFERENCES Poll(pollId),
  notificationDate DATE NOT NULL,
  notificationType NotificationTypes NOT NULL,
  notificationStatus BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE Vote(
  voterId INTEGER REFERENCES Users (userId) ON UPDATE CASCADE ON DELETE CASCADE,
  commentId INTEGER REFERENCES Comment (commentId) ON UPDATE CASCADE ON DELETE CASCADE,
  type BOOLEAN NOT NULL,
  PRIMARY KEY(voterId, commentId)
);

CREATE TABLE PollOption(
  pollOptionId SERIAL NOT NULL,
  optionContent TEXT NOT NULL
);

CREATE TABLE Answer(
  userId INTEGER REFERENCES Users (userId) ON UPDATE CASCADE ON DELETE CASCADE,
  pollId INTEGER REFERENCES Poll (pollId) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY(userId, pollId)
);

CREATE TABLE Upload(
  uploadId SERIAL PRIMARY KEY,
  commentId INTEGER NOT NULL REFERENCES Comment (commentId) ON UPDATE CASCADE,
  fileName TEXT NOT NULL
);

CREATE TABLE Event_Category(
  eventId INTEGER NOT NULL REFERENCES Event (eventId) ON UPDATE CASCADE,
  categoryId INTEGER NOT NULL REFERENCES Category (categoryId) ON UPDATE CASCADE,
  PRIMARY KEY (eventId,categoryId)
);

CREATE TABLE Event_Tag(
  eventId INTEGER NOT NULL REFERENCES Event (eventId) ON UPDATE CASCADE,
  tagId INTEGER NOT NULL REFERENCES Tag (tagId) ON UPDATE CASCADE,
  PRIMARY KEY (eventId,tagId)
);


-----------------------------------------
-- Indexes
-----------------------------------------

CREATE INDEX comments_event ON Comment USING hash (eventId);
CREATE INDEX comments_upload ON Upload USING hash (commentId);
CREATE INDEX notification_receiver ON Notification USING hash (receiverId);

ALTER TABLE Event ADD COLUMN tsvectors TSVECTOR; 
CREATE INDEX event_search ON Event USING GIST (tsvectors);

-----------------------------------------
-- Triggers
-----------------------------------------

CREATE FUNCTION insert_attendee_invitation() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.invitationStatus && NEW.inviteeId NOT IN (SELECT Attendee.attendeeId FROM Attendee
    WHERE Attendee.eventId==NEW.eventId)) THEN
        INSERT INTO Attendee(attendeeId,eventId)
        VALUES (NEW.inviteeId,NEW.eventId);
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER attendee_inserted
    AFTER UPDATE ON Invitation
    EXECUTE PROCEDURE insert_attendee_invitation();

CREATE FUNCTION insert_attendee_request() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.requestStatus && NEW.requesterId NOT IN (SELECT Attendee.attendeeId FROM Attendee
    WHERE Attendee.eventId==NEW.requesterId)) THEN
        INSERT INTO Attendee(attendeeId,eventId)
        VALUES (NEW.requesterId,NEW.eventId);
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER joinUserEventRequestAccepted
    AFTER UPDATE ON JoinRequest
    EXECUTE PROCEDURE insert_attendee_request();


CREATE FUNCTION eventChange() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((NEW.eventStart != OLD.eventStart) || (NEW.eventEnd != OLD.eventEnd)) THEN
        INSERT INTO Notification (receiverId,eventId,notificationDate,notificationType)
        SELECT userId,eventId, DATE('now'),'EventChange'
        FROM Attendee WHERE NEW.eventId == Attendee.attendeeId;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER eventChange_notification
    AFTER UPDATE ON Event
    EXECUTE PROCEDURE eventChange();


CREATE FUNCTION inviteAccepted() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.invitationStatus) THEN
        INSERT INTO Notification (receiverId,invitationId,notificationDate,notificationType)
        VALUES(NEW.inviterId,NEW.invitationId, DATE('now'),'InviteAccepted');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER notification_invite_accepted
    AFTER UPDATE ON Invitation
    EXECUTE PROCEDURE inviteAccepted();


CREATE FUNCTION newInvitation() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO Notification (receiverId,invitationId,notificationDate,notificationType)
    VALUES(NEW.inviteeId,NEW.invitationId, DATE('now'),'NewInvitation');
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER new_invitation
    AFTER INSERT ON Invitation
    EXECUTE PROCEDURE newInvitation();


CREATE FUNCTION joinRequestReviewed() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO Notification (receiverId,joinRequestId,notificationDate,notificationType)
    VALUES(NEW.requesterId,NEW.joinRequestId, DATE('now'),'JoinRequestReviewed');
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER join_request_reviewed
    AFTER UPDATE ON JoinRequest
    EXECUTE PROCEDURE joinRequestReviewed();


CREATE FUNCTION organizerRequestReviewed() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO Notification (receiverId,organizerRequestId,notificationDate,notificationType)
    VALUES(New.requesterId,New.organizerRequestId, DATE('now'),'OrganizerRequestReviewed');
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER organizer_request_reviewed
    AFTER UPDATE ON OrganizerRequest
    EXECUTE PROCEDURE organizerRequestReviewed();


CREATE FUNCTION reportReviewed() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.reportStatus) THEN
        INSERT INTO Notification (receiverId,reportId,notificationDate,notificationType)
        VALUES(NEW.reporterId,NEW.reportId, DATE('now'),'ReportReviewed');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER report_reviewed
    AFTER UPDATE ON Report
    EXECUTE PROCEDURE reportReviewed();


CREATE FUNCTION newPoll() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO Notification (receiverId,pollId,notificationDate,notificationType)
    SELECT userId,NEW.pollId, DATE('now'),'NewPoll'
    FROM Attendee WHERE NEW.eventId == Attendee.eventId;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER new_poll_notification
    AFTER INSERT ON Poll
    EXECUTE PROCEDURE newPoll();


CREATE FUNCTION updateUserToOrg() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.requestStatus) THEN
        UPDATE Users 
        SET userType = 'Organizer'
        WHERE NEW.requesterId==Users.userId;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER update_user_to_organization
    AFTER UPDATE ON OrganizerRequest
    EXECUTE PROCEDURE updateUserToOrg();


CREATE FUNCTION deleteUser() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.accountStatus=='Disabled') THEN
        DELETE FROM Atendee
        WHERE attendeeId = NEW.userId;
        
        DELETE FROM JoinRequest
        WHERE requesterId = NEW.userId AND requestStatus=NULL;

        DELETE FROM OrganizerRequest
        WHERE requesterId = NEW.userId AND requestStatus= FALSE;

        DELETE FROM Notification
        WHERE receiverId = NEW.userId;

        UPDATE Users 
        SET 
        username = CONCAT('Anonymous',userId),
        name='Anonymous',
        email=CONCAT('Deleted',userId),
        password = 'Deleted',
        userPhoto = NULL,
        userTypes = NULL
        WHERE NEW.userId==Users.userId;


    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;


CREATE TRIGGER user_deleted
    AFTER UPDATE ON Users
    EXECUTE PROCEDURE deleteUser();


CREATE FUNCTION eventCancelled() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.eventCanceled ==TRUE) THEN
        DELETE FROM Atendee
        WHERE eventId = NEW.eventId;

        DELETE FROM JoinRequest
        WHERE eventId = NEW.eventId;

    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;


CREATE TRIGGER event_cancelled
    AFTER UPDATE ON Event
    EXECUTE PROCEDURE eventCancelled();


CREATE FUNCTION event_search_update() RETURNS TRIGGER AS
$BODY$
BEGIN

  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.eventName), 'A') ||
      setweight(to_tsvector('english', NEW.description), 'B')
    );
  END IF;

  IF TG_OP = 'UPDATE' THEN
      IF (NEW.eventName <> OLD.eventName OR NEW.description <> OLD.description) THEN
        NEW.tsvectors = (
          setweight(to_tsvector('english', NEW.eventName), 'A') ||
          setweight(to_tsvector('english', NEW.description), 'B')
        );
      END IF;
  END IF;

  RETURN NEW;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER event_search_update
  BEFORE INSERT OR UPDATE ON Event
  FOR EACH ROW
  EXECUTE PROCEDURE event_search_update();
