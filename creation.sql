create schema if not exists lbaw2252;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS attendee CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS tag CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS invitation CASCADE;
DROP TABLE IF EXISTS poll CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS joinrequest CASCADE;
DROP TABLE IF EXISTS organizerrequest CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS vote CASCADE;
DROP TABLE IF EXISTS polloption CASCADE;
DROP TABLE IF EXISTS answer CASCADE;
DROP TABLE IF EXISTS upload CASCADE;
DROP TABLE IF EXISTS event_category CASCADE;
DROP TABLE IF EXISTS event_tag CASCADE;
DROP TABLE IF EXISTS contact CASCADE;

DROP TYPE IF EXISTS notificationtype;
DROP TYPE IF EXISTS accountstatus;
DROP TYPE IF EXISTS usertypes;

CREATE TYPE notificationtype AS ENUM ('EventChange','JoinRequestReviewed','OrganizerRequestReviewed','InviteReceived','InviteAccepted','NewPoll');
CREATE TYPE accountstatus AS ENUM ('Active','Disabled','Blocked');
CREATE TYPE usertypes AS ENUM ('User','Organizer','Admin');

CREATE TABLE users(
  userid SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL CONSTRAINT unique_usernam_uk UNIQUE,
  name VARCHAR(150) NOT NULL, 
  email TEXT NOT NULL CONSTRAINT user_email_uk UNIQUE,
  password TEXT NOT NULL,
  userphoto TEXT,
  accountstatus accountstatus NOT NULL,
  usertype usertypes NOT NULL,
  remember_token TEXT -- Laravel's remember me functionality
);

CREATE TABLE event(
    eventid SERIAL PRIMARY KEY,
    userid INTEGER REFERENCES users (userid) ON DELETE SET NULL ON UPDATE CASCADE,
    eventname TEXT NOT NULL CONSTRAINT unique_eventname UNIQUE,
    public BOOLEAN NOT NULL,
    eventaddress TEXT NOT NULL,
    description TEXT NOT NULL,
    eventcanceled BOOLEAN NOT NULL DEFAULT FALSE,
    eventphoto TEXT NOT NULL,
    startdate DATE NOT NULL,
    enddate DATE NOT NULL,
    CONSTRAINT end_after_start_ck CHECK (enddate > startdate)
);

CREATE TABLE attendee(
  attendeeid INTEGER NOT NULL REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE,
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE,
  PRIMARY KEY(attendeeid, eventid)
);

CREATE TABLE category(
  categoryid SERIAL PRIMARY KEY,
  categoryname TEXT NOT NULL CONSTRAINT category_uk UNIQUE
);

CREATE TABLE tag(
  tagid SERIAL PRIMARY KEY,
  tagname TEXT NOT NULL CONSTRAINT tag_uk UNIQUE
);

CREATE TABLE report(
  reportid SERIAL PRIMARY KEY,
  reporterid INTEGER REFERENCES users (userid) ON DELETE SET NULL ON UPDATE CASCADE,
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE,
  message TEXT NOT NULL,
  reportstatus BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE invitation(
  invitationid SERIAL PRIMARY KEY,
  inviterid INTEGER NOT NULL REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE,
  inviteeid INTEGER NOT NULL REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE,
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE,
  invitationstatus BOOLEAN,
  CONSTRAINT invite_To_Self_ck CHECK (inviterid != inviteeid)
);

CREATE TABLE poll(
  pollid SERIAL PRIMARY KEY,
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE,
  pollcontent TEXT NOT NULL
);

CREATE TABLE comment(
  commentid SERIAL PRIMARY KEY,
  authorId INTEGER REFERENCES users (userid) ON DELETE SET NULL ON UPDATE CASCADE,
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE,
  parentId INTEGER REFERENCES comment (commentid) ON DELETE CASCADE ON UPDATE CASCADE,
  commentcontent TEXT NOT NULL,
  commentdate DATE NOT NULL
);

CREATE TABLE joinrequest(
  joinrequestid SERIAL PRIMARY KEY,
  requesterid INTEGER NOT NULL REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE,
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE,
  requeststatus BOOLEAN
);

CREATE TABLE organizerrequest(
  organizerrequestid SERIAL PRIMARY KEY,
  requesterid INTEGER NOT NULL REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE,
  requeststatus BOOLEAN
);

CREATE TABLE notification(
  notificationid SERIAL PRIMARY KEY,
  receiverid INTEGER NOT NULL REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE,
  eventid INTEGER REFERENCES event (eventid) ON DELETE CASCADE ON UPDATE CASCADE,
  joinrequestid INTEGER REFERENCES joinrequest (joinrequestid) ON DELETE CASCADE ON UPDATE CASCADE,
  organizerrequestid INTEGER REFERENCES organizerrequest (organizerrequestid) ON DELETE CASCADE ON UPDATE CASCADE,
  invitationid INTEGER REFERENCES invitation (invitationid) ON DELETE CASCADE ON UPDATE CASCADE,
  pollid INTEGER REFERENCES poll (pollid) ON DELETE CASCADE ON UPDATE CASCADE,
  notificationdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  notificationtype notificationtype NOT NULL,
  notificationstatus BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE vote(
  voterid INTEGER REFERENCES users (userid) ON UPDATE CASCADE ON DELETE CASCADE,
  commentid INTEGER REFERENCES comment (commentid) ON UPDATE CASCADE ON DELETE CASCADE,
  type BOOLEAN NOT NULL,
  PRIMARY KEY(voterid, commentid)
);

CREATE TABLE polloption(
  polloptionid SERIAL NOT NULL,
  optioncontent TEXT NOT NULL
);

CREATE TABLE answer(
  userid INTEGER REFERENCES users (userid) ON UPDATE CASCADE ON DELETE CASCADE,
  pollid INTEGER REFERENCES poll (pollid) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY(userid, pollid)
);

CREATE TABLE upload(
  uploadid SERIAL PRIMARY KEY,
  commentid INTEGER NOT NULL REFERENCES comment (commentid) ON UPDATE CASCADE ON DELETE CASCADE,
  fileName TEXT NOT NULL
);

CREATE TABLE event_category(
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE ON DELETE CASCADE,
  categoryid INTEGER NOT NULL REFERENCES category (categoryid) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (eventid,categoryid)
);

CREATE TABLE event_tag(
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE ON DELETE CASCADE,
  tagid INTEGER NOT NULL REFERENCES tag (tagid) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (eventid,tagid)
);

-- Added during PA development
CREATE TABLE contact(
  contactid SERIAL PRIMARY KEY,
  name VARCHAR(150) NOT NULL, 
  email TEXT NOT NULL,
  subject TEXT NOT NULL,
  message TEXT NOT NULL
);

-----------------------------------------
-- Indexes
-----------------------------------------


DROP INDEX IF EXISTS comments_event;
DROP INDEX IF EXISTS comments_upload;
DROP INDEX IF EXISTS notification_receiver;
DROP INDEX IF EXISTS event_search;
DROP INDEX IF EXISTS user_search;


CREATE INDEX comments_event ON comment USING hash (eventid);
CREATE INDEX comments_upload ON upload USING hash (commentid);
CREATE INDEX notification_receiver ON notification USING hash (receiverid);

ALTER TABLE event ADD COLUMN tsvectors TSVECTOR; 
CREATE INDEX event_search ON event USING GIST (tsvectors);

ALTER TABLE users ADD COLUMN tsvectors TSVECTOR;
CREATE INDEX user_search ON users USING GIST (tsvectors);

-----------------------------------------
-- Triggers
-----------------------------------------



DROP FUNCTION IF EXISTS insert_attendee_invitation ;
DROP TRIGGER IF EXISTS attendee_inserted ON invitation;
DROP FUNCTION IF EXISTS insert_attendee_request;
DROP TRIGGER IF EXISTS joinUsereventRequestAccepted ON joinrequest;
DROP FUNCTION IF EXISTS EventChange;
DROP TRIGGER IF EXISTS EventChange_notification ON notification;
DROP FUNCTION IF EXISTS InviteAccepted;
DROP TRIGGER IF EXISTS notification_invite_accepted ON invitation;
DROP FUNCTION IF EXISTS newinvitation;
DROP TRIGGER IF EXISTS new_invitation ON invitation;
DROP FUNCTION IF EXISTS JoinRequestReviewed;
DROP TRIGGER IF EXISTS join_request_reviewed ON joinrequest;
DROP FUNCTION IF EXISTS OrganizerRequestReviewed;
DROP TRIGGER IF EXISTS organizer_request_reviewed ON organizerrequest;
DROP FUNCTION IF EXISTS reportReviewed;
DROP TRIGGER IF EXISTS report_reviewed ON report;
DROP FUNCTION IF EXISTS NewPoll;
DROP TRIGGER IF EXISTS new_poll_notification ON poll;
DROP FUNCTION IF EXISTS updateUserToOrg;
DROP TRIGGER IF EXISTS update_user_to_organization ON organizerrequest;
DROP FUNCTION IF EXISTS eventCancelled;
DROP TRIGGER IF EXISTS event_cancelled ON event;
DROP FUNCTION IF EXISTS NewEvent;
DROP TRIGGER IF EXISTS new_event ON event;
DROP FUNCTION IF EXISTS event_search_update;
DROP TRIGGER IF EXISTS event_search_update ON event;
DROP FUNCTION IF EXISTS user_search_update;
DROP TRIGGER IF EXISTS user_search_update ON users;



CREATE FUNCTION insert_attendee_invitation() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.invitationstatus AND NEW.inviteeid NOT IN (SELECT attendee.attendeeid FROM attendee
    WHERE attendee.eventid=NEW.eventid)) THEN
        INSERT INTO attendee(attendeeid,eventid)
        VALUES (NEW.inviteeid,NEW.eventid);
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER attendee_inserted
    AFTER UPDATE ON invitation
    FOR EACH ROW
    EXECUTE PROCEDURE insert_attendee_invitation();

CREATE FUNCTION insert_attendee_request() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.requeststatus && NEW.requesterid NOT IN (SELECT attendee.attendeeid FROM attendee
    WHERE attendee.eventid=NEW.requesterid)) THEN
        INSERT INTO attendee(attendeeid,eventid)
        VALUES (NEW.requesterid,NEW.eventid);
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER joinUsereventRequestAccepted
    AFTER UPDATE ON joinrequest
    FOR EACH ROW
    EXECUTE PROCEDURE insert_attendee_request();


CREATE FUNCTION EventChange() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((NEW.startdate != OLD.startdate) OR (NEW.enddate != OLD.enddate)) THEN
        INSERT INTO notification (receiverid,eventid,notificationtype)
        SELECT userid,eventid,'EventChange'
        FROM attendee WHERE NEW.eventid = attendee.eventid;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER EventChange_notification
    AFTER UPDATE ON event
    FOR EACH ROW
    EXECUTE PROCEDURE EventChange();


CREATE FUNCTION InviteAccepted() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.invitationstatus) THEN
        INSERT INTO notification (receiverid,invitationid,notificationtype)
        VALUES(NEW.inviterid,NEW.invitationid,'InviteAccepted');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER notification_invite_accepted
    AFTER UPDATE ON invitation
    FOR EACH ROW
    EXECUTE PROCEDURE InviteAccepted();


CREATE FUNCTION newinvitation() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (receiverid,invitationid,notificationtype)
    VALUES(NEW.inviteeid, NEW.invitationid,'InviteReceived');
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER new_invitation
    AFTER INSERT ON invitation
    FOR EACH ROW
    EXECUTE PROCEDURE newinvitation();


CREATE FUNCTION JoinRequestReviewed() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (receiverid,joinrequestid,notificationtype)
    VALUES(NEW.requesterid,NEW.joinrequestid,'JoinRequestReviewed');
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER join_request_reviewed
    AFTER UPDATE ON joinrequest
    FOR EACH ROW
    EXECUTE PROCEDURE JoinRequestReviewed();


CREATE FUNCTION OrganizerRequestReviewed() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF (NEW.requeststatus) THEN
    INSERT INTO notification (receiverid,organizerrequestid,notificationtype)
    VALUES(NEW.requesterid,NEW.organizerrequestid,'OrganizerRequestReviewed');
  END IF;  
  RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER organizer_request_reviewed
    AFTER UPDATE ON organizerrequest
    FOR EACH ROW
    EXECUTE PROCEDURE OrganizerRequestReviewed();


CREATE FUNCTION NewPoll() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (receiverid,pollid,notificationtype)
    SELECT attendeeid,NEW.pollid,'NewPoll'
    FROM attendee WHERE NEW.eventid = attendee.eventid;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER new_poll_notification
    AFTER INSERT ON poll
    FOR EACH ROW
    EXECUTE PROCEDURE NewPoll();


CREATE FUNCTION updateUserToOrg() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.requeststatus = TRUE) THEN
        UPDATE users 
        SET usertype = 'Organizer'
        WHERE NEW.requesterid=users.userid;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER update_user_to_organization
    AFTER UPDATE ON organizerrequest
    FOR EACH ROW
    EXECUTE PROCEDURE updateUserToOrg();


CREATE FUNCTION eventCancelled() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.eventcanceled =TRUE) THEN
        DELETE FROM Atendee
        WHERE eventid = NEW.eventid;

        DELETE FROM joinrequest
        WHERE eventid = NEW.eventid;

    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;


CREATE TRIGGER event_cancelled
    AFTER UPDATE ON event
    FOR EACH ROW
    EXECUTE PROCEDURE eventCancelled();



CREATE FUNCTION NewEvent() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO attendee (attendeeid,eventid)
    VALUES(NEW.userid,NEW.eventid);
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER new_event
    AFTER INSERT ON event
    FOR EACH ROW
    EXECUTE PROCEDURE NewEvent();

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
  BEFORE INSERT OR UPDATE ON event
  FOR EACH ROW
  EXECUTE PROCEDURE event_search_update();

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS
$BODY$
  BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.username), 'A') ||
      setweight(to_tsvector('english', NEW.name), 'B')
    );
  END IF;

  IF TG_OP = 'UPDATE' THEN
      IF (NEW.username <> OLD.username OR NEW.name <> OLD.name) THEN
        NEW.tsvectors = (
          setweight(to_tsvector('english', NEW.username), 'A') ||
          setweight(to_tsvector('english', NEW.name), 'B')
        );
      END IF;
  END IF;

  RETURN NEW;
END 
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
  BEFORE INSERT OR UPDATE ON users
  FOR EACH ROW
  EXECUTE PROCEDURE user_search_update();
