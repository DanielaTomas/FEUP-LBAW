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
  requestStatus BOOLEAN NOT NULL DEFAULT FALSE
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