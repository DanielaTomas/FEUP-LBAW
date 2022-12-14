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

CREATE TABLE Report(
  reportId SERIAL PRIMARY KEY,
  reporterId INTEGER REFERENCES users (userid) ON DELETE SET NULL ON UPDATE CASCADE,
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE,
  message TEXT NOT NULL,
  reportstatus BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE invitation(
  invitationid SERIAL PRIMARY KEY,
  inviterId INTEGER NOT NULL REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE,
  inviteeId INTEGER NOT NULL REFERENCES users (userid) ON DELETE CASCADE ON UPDATE CASCADE,
  eventid INTEGER NOT NULL REFERENCES event (eventid) ON UPDATE CASCADE,
  invitationstatus BOOLEAN,
  CONSTRAINT invite_To_Self_ck CHECK (inviterId != inviteeId)
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
  is_read BOOLEAN DEFAULT false,
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
DROP FUNCTION IF EXISTS deleteUser;
DROP TRIGGER IF EXISTS user_deleted ON users;
DROP FUNCTION IF EXISTS eventCancelled;
DROP TRIGGER IF EXISTS event_cancelled ON event;
DROP FUNCTION IF EXISTS event_search_update;
DROP TRIGGER IF EXISTS event_search_update ON event;
DROP FUNCTION IF EXISTS user_search_update;
DROP TRIGGER IF EXISTS user_search_update ON users;



CREATE FUNCTION insert_attendee_invitation() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.invitationstatus AND NEW.inviteeId NOT IN (SELECT attendee.attendeeid FROM attendee
    WHERE attendee.eventid=NEW.eventid)) THEN
        INSERT INTO attendee(attendeeid,eventid)
        VALUES (NEW.inviteeId,NEW.eventid);
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER attendee_inserted
    AFTER UPDATE ON invitation
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
    EXECUTE PROCEDURE insert_attendee_request();


CREATE FUNCTION EventChange() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((NEW.startdate != OLD.startdate) OR (NEW.enddate != OLD.enddate)) THEN
        INSERT INTO notification (receiverid,eventid,notificationdate,notificationtype)
        SELECT userid,eventid, DATE('now'),'EventChange'
        FROM attendee WHERE NEW.eventid = attendee.eventid;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER EventChange_notification
    AFTER UPDATE ON event
    EXECUTE PROCEDURE EventChange();


CREATE FUNCTION InviteAccepted() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.invitationstatus) THEN
        INSERT INTO notification (receiverid,invitationid,notificationdate,notificationtype)
        VALUES(NEW.inviterId,NEW.invitationid, DATE('now'),'InviteAccepted');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER notification_invite_accepted
    AFTER UPDATE ON invitation
    EXECUTE PROCEDURE InviteAccepted();


CREATE FUNCTION newinvitation() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.invitationstatus) THEN
      INSERT INTO notification (receiverid,invitationid,notificationdate,notificationtype)
      VALUES(NEW.inviteeId, NEW.invitationid, DATE('now'),'InviteReceived');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER new_invitation
    AFTER INSERT ON invitation
    EXECUTE PROCEDURE newinvitation();


CREATE FUNCTION JoinRequestReviewed() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (receiverid,joinrequestid,notificationdate,notificationtype)
    VALUES(NEW.requesterid,NEW.joinrequestid, DATE('now'),'JoinRequestReviewed');
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER join_request_reviewed
    AFTER UPDATE ON joinrequest
    EXECUTE PROCEDURE JoinRequestReviewed();


CREATE FUNCTION OrganizerRequestReviewed() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF (NEW.requeststatus) THEN
    INSERT INTO notification (receiverid,organizerrequestid,notificationdate,notificationtype)
    VALUES(NEW.requesterid,NEW.organizerrequestid, DATE('now'),'OrganizerRequestReviewed');
  END IF;  
  RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER organizer_request_reviewed
    AFTER UPDATE ON organizerrequest
    EXECUTE PROCEDURE OrganizerRequestReviewed();


CREATE FUNCTION NewPoll() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (receiverid,pollid,notificationdate,notificationtype)
    SELECT attendeeid,NEW.pollid, DATE('now'),'NewPoll'
    FROM attendee WHERE NEW.eventid = attendee.eventid;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

CREATE TRIGGER new_poll_notification
    AFTER INSERT ON poll
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
    EXECUTE PROCEDURE updateUserToOrg();


CREATE FUNCTION deleteUser() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.accountstatus='Disabled') THEN
        DELETE FROM Atendee
        WHERE attendeeid = NEW.userid;
        
        DELETE FROM joinrequest
        WHERE requesterid = NEW.userid AND requeststatus=NULL;

        DELETE FROM organizerrequest
        WHERE requesterid = NEW.userid AND requeststatus=NULL;

        DELETE FROM notification
        WHERE receiverid = NEW.userid;

        UPDATE users 
        SET 
        username = CONCAT('Anonymous',userid),
        name='Anonymous',
        email=CONCAT('Deleted',userid),
        password = 'Deleted',
        userphoto = NULL,
        usertypes = NULL
        WHERE NEW.userid=users.userid;


    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;


CREATE TRIGGER user_deleted
    AFTER UPDATE ON users
    EXECUTE PROCEDURE deleteUser();


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

------------------------------------------------------------------------------------------------------

---1234
insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('mfalcus0', 'Micky Falcus', 'mfalcus0@google.com.hk', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJ6SURBVDjLjZO7T1NhGMY7Mji6uJgYt8bElTjof6CDg4sMSqIxJsRGB5F4TwQSIg1QKC0KWmkZEEsKtEcSxF5ohV5pKSicXqX3aqGn957z+PUEGopiGJ583/A+v3znvPkJAAjWR0VNJG0kGhKahCFhXcN3YBFfx8Kry6ym4xIzce88/fbWGY2k5WRb77UTTbWuYA9gDGg7EVmSIOF4g5T7HZKuMcSW5djWDyL0uRf0dCc8inYYxTcw9fAiCMBYB3gVj1z7gLhNTjKCqHkYP79KENC9Bq3uxrrqORzy+9D3tPAAccspVx1gWg0KbaZFbGllWFM+xrKkFQudV0CeDfJsjN4+C2nracjunoPq5VXIBrowMK4V1gG1LGyWdbZwCalsBYUyh2KFQzpXxVqkAGswD3+qBDpZwow9iYE5v26/VwfUQnnznyhvjguQYabIIpKpYD1ahI8UTT92MUSFuP5Z/9TBTgOgFrVjp3nakaG/0VmEfpX58pwzjUEquNk362s+PP8XYD/KpYTBHmRg9Wch0QX1R80dCZhYipudYQY2Auib8RmODVCa4hfUK4ngaiiLNFNFdKeCWWscXZMbWy9Unv9/gsIQU09a4pwvUeA3Uapy2C2wCKXL0DqTePLexbWPOv79E8f0UWrencZ2poxciUWZlKssB4bcHeE83NsFuMgpo2iIpMuNa1TNu4XjhggWvb+R2K3wZdLlAZl8Fd9jRb5sD+Xx0RJBx5gdom6VsMEFDyWF0WyCeSOFcDKPnRxZYTQL5Rc/nn1w4oFsBaIhC3r6FRh5erPRhYMyHdeFw4C6zkRhmijM7CnMu0AUZonCDCnRJBqSus5/ABD6Ba5CkQS8AAAAAElFTkSuQmCC', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('esergent1', 'Elfrida Sergent', 'esergent1@trellian.com', '$2a$12$GNYQT3cnVKmhgi5FMyjBuekVSDuYQ9J3brx.1YDQ9vyDOhzX5/4U6', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIkSURBVDjLjZPLaxpRGMWzsHQTpLvusgiF7rvuqn9L/4Muu/UxvlAUFa0SUWsU32+jg0iLi1KIohhREWnxTdU4aG2E5vS7AxHSWJvFwJ3H79xzzv3mCMDRoSudTksTiUQ+Fotx4XBY8vf7x8CFaDS6CoVCQiAQ4Hw+n+RRAqlUShqPxxm8Ho/HWK1WIPjG4/FwLpfr6UGBOzgSiayHwyFoDYoAQRBwdnb22+FwJG0225O9AslkUkof83cwA8/Pz0FxsFgsRCcECxaLhTOZTJK9MJW1HgwGIPvMNsgRLjsD6NMNxL5+g9lsvjUajSW9Xv9sB1NZUgJ4KkuEyQG8Xi9IFLPZDOaLJt5++IJ3nkt87w9BsKDVal/vhckBqCwx+3Q6RalUwo/ZHO8DFQQ+NZHgP0Oj0Ww5jnsjCtBufDAY3E4mE5AI3G63mJ3BJI5isYher4dGq4uPcZ7BNwS/2pVIcN7v99/2+310Oh3k83kRZg4ymQy63S4ajQayuQuo1eqNUqk8vXeM1LJQq9VwfX2NdruNVqslOmDNX11doVqtIpvNQqVS/ST45MEgUVmjcrksfjSfz9FsNkURtmulUhFdMFihUJzsHWWarOdOp3NbKBTEo2OtL5dL1Ot18DwPyvtLLpef7hu63cJutx9brdYp240dIcudy+UYvCH45b9G/t4NTdaxwWAYsfysQMq7kclkLw79cA8e6HS6Y2p6RLDwP5hdfwD40PFHzWBC4QAAAABJRU5ErkJggg==', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('glanahan2', 'Gaultiero Lanahan', 'glanahan2@rediff.com', '$2a$12$aIJGp62nFW6Qz2Bmyo.2ouzpalZjMqLZs2s06H2tYqcCLgpSQt0zG', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAITSURBVDjLpZNNSJRBGMd/s7q50ZoWISZSRAgF5VpELFJ0CK9RneokEl1qO3URpFNCXqIO0aFLEF0iiAj6JBB0WYwOoikRJiXUYqbsumv7zvu+M/N02BV30YPgc5h5GPh/zMz/USLCVirCFqt+tZGfb8UUFxEJEBMiNkRMgBgfsT6EGms0YjwINU0Xn6haAmuIHrm0TkEEFFQWQCD3/PJ6B37+N9tFEOeVDxSIOEAhrDGoSAMSehtcwRhcMI8pfgLnIxKUdxeA04jTiPPYtucCLixtQGB9wCBOg4QVUDVYI64EYpBgAwdmZalsuUbZwzldIfHAeWUR8289gbMaPTOK8b+DDUAMVheI7W8pKzuNWA/E1byBWg3S4oteibZ0EO86DzhcMEdx/BkN+3aBlBie1YzMOZY9j6CU489K/tabOxOD9VVMhAuT5D6m2dl9FaUUTkKQEu+/FZny45w5fYL23R0MT79kbGr0djLV1hyp/u/Gk72E+b/kR+5VwBqxmtdfc3QdSmAjlsTeHqwKSR7tBri+FmWjUXURdhy/gphmiplX1MUSxFr7WCgsEVVxzh2+AcDNs4842NIJEKvKgSb37j5iNBJ6BN4XmM1Q+vyUQiFgOpthIpumv+cxQx/6iNU1AGi1mWlMptoG2w80DXR3nqKj9Rgz8+NkJtP8+rF8V212nJOptiHgGtAIFIGHYw+y/f8B3ntD1Kp2NbQAAAAASUVORK5CYII=', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('dblackader3', 'Darlene Blackader', 'dblackader3@shareasale.com', '$2a$12$rkOFfYybMiOktfTnAX6VAewV7hKHGF.HvVKk6sWofjWUE6ufylRYS', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJZSURBVDjLtZNfSFNRHMdvzHqPIEIo8qEgKIrwIa4PE+ceZo0cC8Ymtq3duz/un7uz7spkxe022dx0e9OxgUqSQdlLYLZemkEhPvRQCGlxqAaW5kutovDbuTeUwgIjOvDlHH7w/fy+v8M5DADmX8T8N0Ay2WuVZenNXwNSqaSGmqVEQq5K0mX8zkR8Akec/loVkMmk2UymT1VfX4ql5ueFQh4TEzcRj1/cACCezu4lXxDE4ppRAel0CrlcFtlsPwYG+jE6Oozx8XGMjAwjJp79BUDcYZ6c9h6s2FxYbnOC6M1+GrkX5fIDlEolTE1NoVgsQpIkCsxCiITWAYQLcUu8H+SUI0ZOWEPLZhtIw/E5hs6KsbFrSCQSkGUZPT09EASBnq8gGPSpgMVW2zyx8bpFK+3aYlFrhG0BOaoDo1xUPj8Inudht9thNpthMBjg83mo3GuAZWJq19EdpPHkD8CRJpADDWC6u2MYGhpELCYiHA7BZGqFVqulsHZ4PK71EWbNh28/NO5Eqalm9V7ztpWnjftB9tbPMV1dEUSjYWr2q5EDAZ8KcTrtNJVTBUxb9ohPose+fLqTxuqzSXy8LmCm4xAe1e+4wUQ6A6y/w8t6vRzrdp9hed4xr4BEUQDHOVTA3WbNQpWakTMC4nbgah3eJRtB6y82PBLaVcO5HDJVdQ2gxF6dvYWf10p8lzrOH58oBbVRVdQEes2rDwV6H9T0+RyD91TEqwGtv97Uhylbai895vZ9rYh1eHt+Kxa4LbhvrPk2qddc2PSvm7bsjtGOL5XYSiLFrNS/A2oTmihPyHNrAAAAAElFTkSuQmCC', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('hhead4', 'Harald Head', 'hhead4@apple.com', '$2a$12$nbHqkY.0JP6.N1d4BTj7mu5W9tRdfzI/V81q61o.RMRhY32c/vy9G', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAF7SURBVDjLpZI/S5thFMVPXt9aakBjIYJvyFycnZw7KDhkcCi41BK30tpkqA5d/QClLv0IKuIQB/8Mje5dVLCCxhQtkg+QFnLPuU8HqYY0Q4h3fLjnx+9ynlQIAY+ZqNfFxcr8ypvtVwN9A2icp/Fr53uq84SlajEv+ZyoacknRWVlwvhwDk6h1qh93lzY+dAV8L5anHL6cpLOFTJPR5F+kkYcxfDgoAt04rR+gtqvq9XK24NPABDfh78V85KWX2QmCmPpMfyx34iiCHRCLngIkAtGwoyD/3L3AFFzyVBSyA5lQRdMwtntOX426qAJyfMEpHB1U1vbLVU//gcgNT08mEHTmqCEox+H1zRubbyulABg9svLY5q+75Wr77q2IOPMyLMRDKRiXDYu0B4GADOum3Gxs4UHAxMoQsFRb9SxubBTal/cLx+udqu3DUAwEJKDLfb8E+M2RRgFQTDrA8CW7gxc/RnQCBPhoU8DaxF0wh9jsH+0d9cGewf8BRKi/IUanjYRAAAAAElFTkSuQmCC', 'Disabled', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('ckirtland5', 'Cathrin Kirtland', 'ckirtland5@fotki.com', '$2a$12$FNoX/oiWL6YfIpa/AyYUZu/RyE65BxRRYCVgNmwmOwZCjZ3xU2nT.', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAKoSURBVDjLpZLbT5JhHMdZ1/VP1Cqrtco21kU3zevWVa3NXOuuedVmoU2jUR6CoRwcw1SYHIzJQQxBUXgLXg6Ga+mIwkBBBp08pYGvEOa393nbWC5Xa1389mzPnu/v+/t+nh8PAO9/6p8FBoMBWq0Wvb29UKlU13ihUAikAoEAfD4fKIrC5OQkxsfHMTo6CrvdDovFApPJBL1ez70tl8vI5XKQy+UxXjAYxPb2Nkql0h8rn89Do9G839jYwNzcHGQyWVoikdTzaJrmLrLZLKamppDJZEDu0uk0PB4PkskknE4n98ZqtSIWi3ETicXimgoDr9eLcDgMl8vF9/v9sNlsfCI2Go18EqOvr49PxEqlkj84OMjlb21trao0cLvdiEajHINUKsUxIM5EHI/HQTmUmKcFGHqixezsLHGHUCjcv+sXRkZGUCgUMDExAZY03+FwECf+sNWEhLs2vZq0YMZeZ+zv7ydi/PaNbK6W6elpJBIJEDFxNpvNiIdUWI4bUS7M4/XwFbwKO9DU1LSz5x7odDpCGj09Peju7kafqg1R62UUl50ofujC2oILkaGbENxp2PnrIr21Xdr3xnzRsPLOimL2AehHZ/Ft1YoZbQ1kwutfdzUYGBg4ypJ+rFarCWl0dnZCIxcgTTWjtKQHM38DdMcZbGUasZ4ag6frwveI4tyBSgMWVgs5FQrFLalUuigVtzWwTi+/sOC2Fm9jM3H1ZyXr2ChyZPxKhCTVwkoDdqdb2LXkFiUSiWBM14wM3YXSJzXnvpmsZSNUcyeTqgfz8Snohyc/+0Unju/K3d7eDpFIhJD8/DqzsoDSGoXiEstgyfJL2VDOx5B7YcSz5iOWPQGy460EO04zgbZTDOvEsE6M7/4x5vm9KoYVMdTdwwzVeIjxCg4GfgDxYPxXmKLFvgAAAABJRU5ErkJggg==', 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mdougary6', 'Merilee Dougary', 'mdougary6@artisteer.com', '$2a$12$x..4MPUpLKYfrL.b6md3AO/gMGPRVtxbaIoreHQEH1K34MyCb1R8e', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAH8SURBVDjLjZPfS1NhGMdXf0VEQhDUhdCN4X0IYT8ghIJQM0KoC4vushZddLELKyRhQQkSFIKEGEkUCI2oxVhepG5zi1xbc0u3cDs7Z+ec/ezT+x62scmmHvhwDrzP93Pe57znsQE2cR0SdAm6d+GwYL/M1LBVBV35fF4plUqVcrlMK8Q6TqdzYrukJuiW4Vwuh67rdbLZLJlMhmQyaUnigVlC05f4+dbB0tQplp92DsnwPimQBaZpUigUrLtE0zQURSGVSqHF37DhGkVZeQdagszKLJ7HvZtNAhmuIQWGYaCqKps/ZkivPqCwPs/Gp0cYvjnKUTe+F9fMJoFoo96zfJZ9K+sLpP33qRhujPANtr7dJPhqmO/PBxX3+PljTYLtqImPpH13qZge9LUrmLEB1FU7sZd9jJw5MljNthYk/KLnxdFqeAjzdz9Z/z3Ck2fRE36qx9pakAjME1y4Lbb9GTMyTD52GUXsZO3ZadTkL6umrSD4ZZrAezvLH54Q915EjwywtXSH8FQf+t+I9V12FLwe6wE1SmjyAi77Qb6Kt3rGe9H+hKzwrgLH9eMUPE4K3gm8jpPMjRwlHfNTLBbr7Cjo7znA2NVOXA/PsThzi2wyah1pI+0E/9rNQQsqMtM4CyfE36fLhb2ERa0mB7BR0CElexjnGnL0O2T2PyFunSz8jchwAAAAAElFTkSuQmCC', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('bbullman7', 'Brandyn Bullman', 'bbullman7@amazon.co.jp', '$2a$12$uQ6hxS1VICoKLTAxN4VIvOCN9GOJYWrL.50xiecGDx4HsOJLuMLHK', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIySURBVDjLpdNdTFJhGAdw120X3XXR5kU33fQxS0+5Yl24lFnQKsvl2nJLM0fmXLNASceKgAv8yBGgJPEhkcIShEEYKuKU1IxcTm0WUDiJ1Fpbm1tZ/855186oLS/k4r/34n2e355z9rwZADLSyX8vCm+WU6fqT38+21S4ztPy1rmK4lXF5Ry//Hwm6LjoHN8QOGOgUOe9iGByCJ7FJ5BMX0ORiosfa1/wTHqQIAQ4VCHbwpXL53iWHPAe7QefJAvq4G2MLY9gcnUcQ0kf/AkvAm4DPvhl6Lq+jwEuESD7inLrCWXJ10BygC56SgpHlofxfGUMjvhjDH7sR1e0Hfq3VmiqKSwOt6CldCcD7CDA3qrOXfRo37tjRojC5SRt81KYIxp4lxx0+mCOaqEON8NeR2Ght5ppBvsTT9Yqai60F/y0vTehPlyBW+FKAliiOvQnPGQKY+Q+TOOdCCjzEPU2/A1wxIaH3a8N0C20ouGVAI3TVVC9kcEa0yO0MgrfkptM0mprwqypGKG2AgaYYYEsqfGFI94D4csy1E6VonlWgt64Fb6EG7aYGTdGK1ETEv6yu+wEcDQeZoA7LHBEJfxkiejQQxczccZtEE8JwHNRKLMK1rRzng6R3xU8kLkdM/oidAh2M8BRFsi7W/Iu38wBty8bXCcdSy6OyfjfUneCbjj34OoeMkHq92+4SP8A95wSTlrA/ISGnxZAmgeV+ewKbwqwi3MZQLQZQP3nFTLnttS73y9CuFIqo/imAAAAAElFTkSuQmCC', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('dwhatmough8', 'Dierdre Whatmough', 'dwhatmough8@va.gov', '$2a$12$WTeWqqwBQY52XF3IrwsUCOMzXit0Oa705TOME9TAeJ.wuudg8Z28G', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAACWSURBVCjPY/jPgB8y0ElB+YHyA8UTcg+kLYjfEP4Bm4ILxQa5Dqn/4xv+M/hdcHXAUFAc8J8hzSH+fzhQgauCjQJWN8Q7RPz3AyqwmWC0QfO/wgKJBWgKwh0C/rsCFRgBTVP4/59BMABNgZ+Dx3+bBghb4j8WK1wdHP4bQRUIYlNgs8DogOYGBaAPBB24DrA40Duo8UEA+kT4W+XS/8wAAAAASUVORK5CYII=', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('jsteptoe9', 'Jojo Steptoe', 'jsteptoe9@theglobeandmail.com', '$2a$12$MPeic3/aCWO8Fqujw86EbOwI4EZW4nWoHeH/34BFdVHzC4KXnRw4u', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADHSURBVCjPhZFBDoIwEEV/peLWlW5ceAcTvY6ncW9i4pm8hgsT2QgGEFLKdygQkKD2Z9pJ5nUyv1XE7zX5U4euD6WGBTatFVZYwhu5GuDKsko2WWhswU9lPB2xxqRqszU24ZMRUyaiiA/eBbk1iAAV/xLlbo8ZMhAglewsiBLgYmUI4wwRJSxyzFsPO916ndazu/ARClhg0drsPKrGkA/bZHrorkKUE8cBuKI3fMkhAkH4/S+IbjI9Vux/jNof4lmBvowL43Lmb/8gdgK2+FpkAAAAAElFTkSuQmCC', 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('pbentkea', 'Patty Bentke', 'pbentkea@hp.com', '$2a$12$H3ksZb9D2lgfH5jS5EFJd.mM7JM.j1CFGCujDM6ojPyviM82Zw1bG', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJKSURBVDjLldFfSFNRHAdw88mXnuqtHu/dufe2e/dHjUl/YEYRRsFaLViYFkUY+CcDwcqiJAjKoCiKCqRw+GCEa6vIdKOJLBPaiqy9CBlkYUUbq83Z3f32uzMX0kZ64AvnHM79nN/53RIAJYXC8+wux/EZQRDiFsu6Y8XOFdxkjG3lOE6jgABYrVbwvFS3BEA6YbNVQTLKWL9hI2RZAWGPFw3IcuVh184axMbDePrEC7vdrgOhJVQgb488MEGdMCH9zozOlgpwBtMr6kvZogBRUvYH7jdjMrQF09HjON3RDoulgvrAnP8FqFTW1NT8PvkjhamPn6BqQCj0jPog6894azCwVUUBuqGUDg15vV7oQ1WzFBWJRBzV1Zt0QK/CT8iyggAdsLlce9RkMkFLDdmsmos+Hx4OwWazgX6xRoi9GHDd4/Hkbs9k0nT7rzygx+FwUBU8hXX+AzBeXG21mOPBYCgHpNMpAmb/ANncXm3tvtwzCLi6ABi5pazwX1QORHsFtedGC6Y+f899+BcgIuJE/W69kQyN9fLNBUCsz9o/E5aBiILROxycjm14Mx7D3NAwSwWkAmvxoYdh9LKAn37xa7LfuCsPTNxT/OqYgpkRGVpYwu2z5Xj+IoL54fUNYLCrHBgUKCI0yrc+YywPvO42RqcfykgO6YCMLz4TTrbW4Whrm+Z279UYW4PzhwxAQELKJ2HsghR81GbenAd626WV1xrFmq4j4jmav7zUIExWKnwVNaxsLsLygzukjoFTQrq7Qbxyxi2WzvfgNzL+mVcak7YgAAAAAElFTkSuQmCC', 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('rbuckleeb', 'Rafael Bucklee', 'rbuckleeb@china.com.cn', '$2a$12$7CItKvaiiEGrW9GdxMDOSe/m0h76BeEY36Ths41IVGQhuDBn29CiO', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAM9SURBVDjLdZPrS1NhHMcPREH/QC96nxURWS+iktC8bEt3skHoi95EbYk5KStqdtPGLKcuczSELnYhK0bzhpfK5nSZed1MPZtzzpq3pnN5tOY2p+fbsxMEUj3wgx9fvr8Pz+X7UACof9VwPb1juC6l2l6T/N5WJdr9P99fgqPxyCYyrLLXpczPMg/xrbcQzOukH0P6xJLBl/Gb/wsYaUpdT4Zlw/Vi55RVi5XgNLilCSy6qhGYrIO79Tw+P4/92v/soNz6JGbjGoCjKVXgaDhi/tpxA4Hvn4m0BHAswr4ejBiOImAvRsitx6JNB2fdSVge7e/su7+X5gFk+LGjgeZ8jkr4vQPwjbVgrIsYP6hhe3MOrreZ8Nvvwm/NQ9D5CMsTesx1q8C8kKBHt+dF5LLCXNCNkLcPvgEtvL0qTJnOwlmbhs57MVieswB+BzD7FtwXHcBcBiYrER5VoUu7K0yRy2JXg+PAjyEsT9ZgwXoL/v48UgpM1op5DTONgPsBOJsCfmMcZhoOYoG5i87SnSxlqznMri4RwM8RAmEArxEBRg1/VyZm6sUIj2iA0RKE2kWYa9wHj0kET3Mq2P4SfNLsYCnGIGRXeIAdWCTbne8kkHcIO7VYaEtDyCwCa4zB3EchZoxJmG6Ix3StEN+7C9FRtI2lyPv+BpAjgO1CYOoNmqu10JQUoqKiAkUFl2AlRxltFKJIdZHXim/no+aBAibV1gVq8FV8iAt/Iy/nwrK3BRW66ygrK4PH44HL5UJbWxvuqHOhU8vhGGZ4rb29nfcoTx9YoQYq45pHjZexNGVC67uXuHpFAcvgIArz5aBpMWQyGbRaLXJzc/meFouRf/4ED7l08VyYIsnaQJIlI+FwKi8cw60CFQ8IjldCJEyA0WiExWKB2WyGwWCAICEOLcot7ghAqVQG/kSZJGtTzvHopuwzUi4CuHnjApISEyEQCCCRSPiK9Anxh1bTjh1tjQAyMjLm13yM7WRJUsVjpRp16PWrp6iqqkJ5eTlycnKgUCj4PqLp9XqfRqOZp2navgYQFRW1LjY2Njo5OfmLTHoqkC3PXM2Wn+GuZQhK09PTE7KyshZJBaRS6c+IJ+L9BchY24ysm0a5AAAAAElFTkSuQmCC', 'Blocked', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('bbeckerc', 'Barbabas Becker', 'bbeckerc@mysql.com', '$2a$12$6grWpzjqrUWQUu1R.qP5.OKY2Y4KYCMyR7BEe4wAnTNVLB36m6SVS', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJISURBVDjLzZNdSBNQHMV98lHovfcSbS7NkshK0zSXKbqmtTHSLTUt1OVHEaUUis6H5kul4QeCIpig+IFFUEIo9jUttWJoyUAJbbvpcjrdfl3tpaAs6qUL5+XCOfec//9cP8DvX+D3fwj4LhVpJT5JiA14i03Ca8oX6xfyxNq5bOExGsSqXi9W0k8Jd6paLB9PWnAdPabbFJAEjYSL+wMwNARjY2CzweQk3sePWGhtxdHSwmJzM866euwWC/ZrZSwdPOJajIhM2hB4TsNd6OmBjg6YmIDRUejsxFtcjD0nh1mjEYfBwLxOx4vERN5WVWHLL0Ts3mffEAjxlZie+qorQb7GzAybZ3AQr1rNrErFx4QEPktMR0fzpqKCaWMWImTvnDNwt2ZzBjLvNpm331teBt3dMDcHVis+rZYFSXRKEWd8PFOVlbzPMCIUe6YcO5XKH7awlpMV4MnM7FsvLYXhYRgfxyeti9hYXHFxzJvNfJBkZ3DYlGOHYtdP17ii1QW4NWl9npLSbwJyBq6oKFZqajj/8AzZ/ToyutLQtafwyx4sn0gO+BKverB65Sq+9nbWqqtxFJg426ulbbKBltd1pDaq2LJIS4djti/uP2RbNRXhlmSZ163vUNP06hZ11lq0TanE1ERGbNlEERoRJPOO3LiZ6Na3pXgu9uZy+6WF2mdmCrpyOVAe7g6/rBz4bVVPtyX7S8u2vHsGLCNVmIeuk16fQliJ4p0iP9D/jzsvLY/pGk+iuZNMaFGw9a8+k7Q8oiwMevL93Ve6KsnKrbmJHAAAAABJRU5ErkJggg==', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('pbromwichd', 'Pooh Bromwich', 'pbromwichd@delicious.com', '$2a$12$vGby4k2Q9bSVctmk6qJBQ.T.KnYLBMTvjTu94c1dvKxW/D8VDBKZq', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAKASURBVDjLpVNLTxNRGOUH+Av6O2Dp0oVdGEE37EzcmHShiRoCMWqDJiQSI6QjREtAHilQU0lQ3hawjEUM2pZSOn1QHi0M0GmdDp2+oMf5LnSsj503OZubc853vu9+twZATTUudcGgoU6DUUPjOYznd4Y/+dXCCxpqW97JJsfnXW7ZE+bX1jcknz8gLa0G+dHFGNdkT5mIQ9zfDM7FF4dde2bfRlQQkzIUtYjSaZkhky0gcZCCe9Uv9M5EzcStmFQMaknsD24quXwJdNTCCVJKEZKG4/wJu1PUApZWvIp1MkQmtcyA+qLYVLkiPpILOPhRgJg+w146j0Qqr5vMzLuFO0MitWMggzoHv8NRbDo9c3Fcf+rSce3JIkND6wJaBkOME4ztw2Jf5khLBkb39xBPPVNUEjnXJL1qXMphN5nDrCeJBrMTcraEw7SKvpH3PGnJoNG7ti7RsJKZIjOoFsYOVNzu2cD6roL6xx/ZXaF0iiHbiERaZuDx+ZkB9U5x57xJRozsZ5m4/tEs3rpFZrB9lNNSFNHXP6gbGD99DfBpJY+MWkLX9A4u3xtlxIGFBBNffTiNKw8mcP9NkL1MOJ6GxWrTW6izOSPcdiKJchlMuHWosuhRUcWzsRgz8MQyCCWyKJ6UMf8lAHP3hD5Ew93hI5PTtSJIco5NmYQEaiG8l0WbYxPfojIrENGqd74aEG52h8+esbJIljGfeXzWpYipY0ZMH5f0NLRMVDkcT6HDOqw0WyZ/LVL1KrfbVswD9nFh2RvBtiizmVDPgS0JUy4f2i29QlPnh79Xufoz3erZMrW+nuKed/Xzlpfd0otOTmrrsPLNHQ7uBhf892f6n+/8E/bIBuJgfmmXAAAAAElFTkSuQmCC', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('cgirardettie', 'Cyrillus Girardetti', 'cgirardettie@dropbox.com', '$2a$12$Bae4xKFJke0VCq/toEP2n.gdv30onGj9tf7bDTCcWUK5JZrb40eFq', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADCSURBVCjPvdCxDYMwEAXQkyhoEBGKIipcRHTuXFk0riwhGgoke4JMwARMkA2Y4G+QCdggE9waxAKHkhJd+Z++z0crnQ9dAtzk4DD4lTpvYaAnJeVcQ7RHg+MBuzdQrCq51JP4PLioIhi4j0DjydLXISibG2dNBD13ix3NqEe1SN5pgeyb5hF0bGODRL2B4p0hlccOlk0EYTXe4tdKSU7/HQzrCATuXDShHAlooXYDZtJQkOGbwpcIb89bDJqvO/X5/ABgCuuOdgJr8AAAAABJRU5ErkJggg==', 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('rdacheyf', 'Rollin Dachey', 'rdacheyf@gizmodo.com', '$2a$12$viNjoc/pkk2tb8Rw98sxD.KsvS3KeHERGQscVESkhDOmPgHHAfNPK', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHdSURBVDjLpZNraxpBFIb3a0ggISmmNISWXmOboKihxpgUNGWNSpvaS6RpKL3Ry//Mh1wgf6PElaCyzq67O09nVjdVlJbSDy8Lw77PmfecMwZg/I/GDw3DCo8HCkZl/RlgGA0e3Yfv7+DbAfLrW+SXOvLTG+SHV/gPbuMZRnsyIDL/OASziMxkkKkUQTJJsLaGn8/iHz6nd+8mQv87Ahg2H9Th/BxZqxEkEgSrq/iVCvLsDK9awtvfxb2zjD2ARID+lVVlbabTgWYTv1rFL5fBUtHbbeTJCb3EQ3ovCnRC6xAgzJtOE+ztheYIEkqbFaS3vY2zuIj77AmtYYDusPy8/zuvunJkDKXM7tYWTiyGWFjAqeQnAD6+7ueNx/FLpRGAru7mcoj5ebqzszil7DggeF/DX1nBN82rzPqrzbRayIsLhJqMPT2N83Sdy2GApwFqRN7jFPL0tF+10cDd3MTZ2AjNUkGCoyO6y9cRxfQowFUbpufr1ct4ZoHg+Dg067zduTmEbq4yi/UkYidDe+kaTcP4ObJIajksPd/eyx3c+N2rvPbMDPbUFPZSLKzcGjKPrbJaDsu+dQO3msfZzeGY2TCvKGYQhdSYeeJjUt21dIcjXQ7U7Kv599f4j/oF55W4g/2e3b8AAAAASUVORK5CYII=', 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('nmayg', 'Noe May', 'nmayg@tmall.com', '$2a$12$SAaRbpQ/X8E28i/HKGVx3eG4o8cSwRwZ1zYVZdYAVfLRYz404fmkO', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAALcSURBVDjLbZNrSFNxGIcPCJNBIAQRISRGYQWF0g27OJgQVmQFmpViZHhLaiaGeCtTQ1OW09QtS83NiR2XF0bZZZjKXNYq8kMUBX7qU30JIsM68+ldUpH64eXA4f/83ud9z/krgPJfjbVFSZkZsb5grEXDY9F4WP+C4Stm+i9GLTy/EC4QcIZnvTA1JOWG14Mw4YDRFnBdmEHNL1gcMNamY/TGMJPd8Molh61wvxbpCoPl0F8EQxdh5BoMlMDN1GGsybp/ASNWC88EDgY8rNcENIqu/m8nZ54ee6aRjnQNl4Q5csGy3zwfMNKyiSdNGpM90rFGY7AigoHSZaJbLbo+unN92DOqsR1fxo2kCK4f1FBlijqDRu3OjQqPzRa8HaJnCeoaGSgKp69gmntV0JsPPWfkeR5sh6dp2hdOvdEoIXArDapjzIrovsPXPT+bq1Avun24LyO6Km3HDDQfNoiuiiNLusb1URerp3ILYgYlUW8U0Z3FJ1tW84MvQ0R3BkcOomvw+/2JXq93o+gaiuyxZHbGQMXmEErXg/OsBKz5oojuLN52WUxmcLt6bClB3U/0mnYL/NPj8fygNSnhXHtM4LL7BCk2gUvWzo9WFvlVke1+4O4FRHdGdHWiGxfcrsm5B1N3LLn2bWS0R8+VDSSj+hsp7DtEQsNK5mq3BgNkBNtxk+hmyWLC/nw26bw8z7GDwVet3H3Z/Bvs9VuweArpelpHjnMv22tCtUBZ5FFl0a8sJdqtp2XeIHztkYmrD/K4cj+LCvcpKu9lYxuvIu32btZdUmaXgqPdbncg9eYG7jy34Jw00+Wro2OiVkLOYB2vJMuZQGS58ja8WNEvChDYo6rq9yPWNXP7GsMD8Q0rArvqw+bSO3fSOlZBhmMvEeXK1KpiJXTRZRL4gMAf7XZ72cLgzVW6bye74llbEvpeYN2St1HgNoETl9qLKH9eXRrC6ZyMg9nZ2SF/3v8CIIKyHGFPw/kAAAAASUVORK5CYII=', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('oarnelyh', 'Ofelia Arnely', 'oarnelyh@furl.net', '$2a$12$ykFgvUQSzcn4qTbqs72GaO0dGZ8sm.hdfAY54FQycqvH.wnuHsh.e', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAKsSURBVDjLpVNdSFNhGH7P2dnZz3FOxfnD/Ns0pzXNEgbdSEhlhBeV9AP90IUV3XhR0EV3CUEXgRBBfxS18CIYXWStKJIo2NxSmw1zGLYlOja3Ua25nW1n39c7KVEwCzrw8vC97/s833Pe7/sYSin8z8euV/R4PPzo6KhqvR7mTw76Hrw/LpDFa5Yqjp1MFELsh9g3cHTr3X9ygOQehYLczynlmpmkIEgcK3A8vXPsytOevwoguRVI2qYtUUHk8ywYmeDEt3AU8msWJFv32Zut6wrsrRMvVHBpCHwMQJUgje3v7mrTSjFXfl3KJKGzjpxaNQOv13tDkqQODMhmszyG0Ww2Mzb763RbY5UiEAiAsTwFTMJFlNUn2JfDjmQmk/mSTqcBMcIiUdLpdM0o1oyJ8zzPM06nc6S+Uqvw+/2HBRUHheKreYOpk4357nkFQVCLonjJZDI1o4iRRdJAMBiERCKhx4Q9Go22I+5bWFjYjI3Pq+mT6yW1XfrCyi1Qxodq9OzIIawP+3y+vIMPrMVimUFyHB3MY2GPQqEYQ+JOtVo9UcT4e2W55BlNaRHk4o+hvLVXq5JJD0k21WUwGPICb5eGiERnPB7XI9H+e+dwaM5Sr/RYKjYdABDHwW0dhIKiFBTTebqrZopzOBwZnJdnSQAJt1Y4mEDcUalJuQtKzQc1xUk8VT8AJZBLvAPD9nOMnCxeZIgYQgH38k20Wq1ZtKRBcp2ahL+3CK7hho6TTUq5C0hm7teZyUEmtEP4EwvP7EOzpy+/qF2+B0jk0EkKcYqXIsGyDbub1JooUCmGRBmMPZrGLgJEnAZdYy00qCPlb/rNG9d8C+6r2+ZajgzqZWwABb5ihq66ezKVCcKTHpge6rdxa70FKZ1Rjt/uSVFC8dfJiqDLCHmktOInriB9Oz6CFbUAAAAASUVORK5CYII=', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('hkohneni', 'Hilliard Kohnen', 'hkohneni@flickr.com', '$2a$12$H79gHsKwoLIWXSZASHEOq.EM3N0lMmL2886H5UdvQj4pRTiuYc5Ie', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAANBSURBVDjLXZNrSFNhGMcPQQQRfYv60meJoiCtROuDZRcEA6MP3ckwS0tlZVlptnXRqavMaqZ5Ka1Jq1bOmWiXs466mqZzLs0103CmLp27HOdlaf+es2xFL/xe/jzv8/+/vA/nMACYsWpmDiEmjEQTMU+o/wvVFs+e64mAP3XGoWLmEtljzXv7vSMsXM37bHp1ZEPyK6+WsM+ifa+O4tyGuJHxzjQ79euJpb4AWwWT6tLv/zY1VI3hd9GOD8oQXtowglvNNhS3DfoQ9DWuB23K1R6nSeLh205+J18LMZex3mPOu41p9qH6aIfuQciPvHd9eGQcgIL7CrmqA3mPO3DvdQ8Uhn6UvGXxSb11Ztz6eHro+TIzeQOYLwXMhq7C+ebGopWebLYHFfo+qNhedFtdGHHxGHaNwdznQnldN0rqe/GoUgajIniys3BhK3kDfINILq7KSXlqQmFDL5R0m7BGnU58/jaICdIC/E/gjqYbcq0F6UoO8aW6K74ZCNveghbtqScm3Kkxo5Nu9vz4Cd7jwe2SUtgoyD05iae1b8B9diJT2Q6hV/D4A3bmcnaRohVZD42wjXsxOjmDKTo4K5bggaoSKRckqNPpwQ5acEKuh9ArePwB2zNr7LFFeohLDejjvRQyA6vTjcuyqz4zZ2hHWtMJiOpjkfDmEGLL1BA8/oBt6U+0u66zkJS34K3FiQF6tNXtxQttI3rsLgxNAymNiSjvzsfVVgkSa2MQmXWrxR8Qduq+OEL8HEl3dZAqzRimgY16AfcMQdpBASfZeJSY81BMSBpTEK3cjUj55rW+gNAEeRDRseV8FUQFHLKUXTD0OsDTPHiPF0bShyujkd8hwyXDaeR9lCK57hjCczb8/dbXHpYdiZOWe8LPPMMB2UuIbnJIvtEA0fV6HM9lsU+xG7ntGTjXlIgc40UkaGKwXrxmwh+g0+nCTCYTXrPcdOixIqw5rsC6JJUPQe+4G4Ws1guQGtIRrz6EkPQgb+Dplb+foNFoFhG8xWKBuqrKvmpPmmTFrlQtYZ9FG3Fj84Sk6QyOVh5EcGogDmTv2eEfYllZ2QKii5gilv//KwtslIaORuRuQvC5QEjzM4apb4lQ/wXCx9fe4QKeWQAAAABJRU5ErkJggg==', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('lsommervillej', 'Lindsey Sommerville', 'lsommervillej@ihg.com', '$2a$12$R1zkONLvhzMVC6sOdHcGHOQv5zxv2vGNc6j5FN/MAb0cMV68h2LCq', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAFzSURBVDjLY/j//z8DPlxYWFgAxA9ANDZ5BiIMeASlH5BswPz58+uampo2kuUCkGYgPg/EQvgsweZk5rlz5zYSoxnDAKBmprq6umONjY1vsmdeamvd9Pzc1N2vv/Zse/k0a/6jZWGT7hWGTLhrEdR7hwOrAfPmzWtob29/XlRc9qdjw8P76fMeTU2c9WBi5LQH7UB6ftS0B9MDe+7k+XfeCvRpu6Xr1XJTEMPP2TMvlkzZ8fhn9JSb+ujO9e+6ZebbcSvMu/Wmm2fzDSv3hmuGsHh+BAptkJ9Llj3e2LDu2SVcfvZqucHm0XhD163+mplLzVVtjHgGar7asO75bUKB51R9Vdih4ooqRkprXPfsXsfm558JGQDCtqWXmDAEi5Y+PjNhx4v/QL8aE2MIhkD8zAcbJ+189d+z5UYOWQZ4t9xsnLjj5f/A3ltLyDIAGDXe7Zue/89b/OiZY8UVNpINAEaNUOWqp38qVj3+DwykQEIGAABS5b0Ghvs3EQAAAABJRU5ErkJggg==', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('bbuzzingk', 'Berke Buzzing', 'bbuzzingk@vinaora.com', '$2a$12$URLkNk9xF.ZLswkDJI3SHOkIP.ldeVTkkeDEAWhYIssAi2sVwBMzi', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAD0SURBVCjPfdExSwJxHMbx/yTc1NrUy+h1+AZ6GUJBaYdiKVwopjmYOASiINJgEVFwUFHo4BIiDtql/SPU5BDUQb8Nomh3J8/we4bP8MBPIOYpexdtPcvyyrO6ETxR5zGwAeiMeOfmxBE8MOKXKsWwA7hjSJceZbJhW1DC5BvJDy+kNRtwzYA2BgYSnUTEAgr0+aBJC0mbe85i/0AOkw4Gn8SH0Yo2CRGMrYEralyOq/SJzrRtBEJVvMoKyJCSyd3zZh2dUMZmZOotuYOIuAuYBKbqlgVcKPN7KhvccnRsAYv49/I0ODA9Lgfgcx1+7Vc8y8/+AURAMO9/VDEvAAAAAElFTkSuQmCC', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('wkensettl', 'Wren Kensett', 'wkensettl@freewebs.com', '$2a$12$xCMI5mdm2MlEdGOyiNpEf.Sw9FJuMkyW6zjspo6PY/3wgnjA1fn0K', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHjSURBVDjLdZO/alVBEMZ/5+TemxAbFUUskqAoSOJNp4KC4AsoPoGFIHY+gA+jiJXaKIiChbETtBYLUbSMRf6Aydndmfks9kRjvHdhGVh2fvN9uzONJK7fe7Ai6algA3FZCAmQqEF/dnihpK1v7x7dPw0woF64Izg3Xl5s1n9uIe0lQYUFCtjc+sVuEqHBKfpVAXB1vLzQXFtdYPHkGFUCoahVo1Y/fnie+bkBV27c5R8A0pHxyhKvPn5hY2MHRQAQeyokFGJze4cuZfav3gLNYDTg7Pklzpw4ijtIQYRwFx6BhdjtCk+erU0CCPfg+/o2o3ZI13WUlLGo58YMg+GIY4dmCWkCAAgPzAspJW5ePFPlV3VI4uHbz5S5IQfy/yooHngxzFser30iFcNcuAVGw3A0Ilt91IkAsyCXQg5QO0szHEIrogkiguwN2acCoJhjnZGKYx4Ujz5WOA2YD1BMU+BBSYVUvNpxkXuIuWgbsOxTHrG3UHIFWIhsgXtQQpTizNBS5jXZQkhkcywZqQQlAjdRwiml7wU5xWLaL1AvZa8WIjALzIRZ7YVWDW5CiIj48Z8F2pYLl1ZR0+AuzEX0UX035mxIkLq0dhDw5vXL97fr5O3rfwQHJhPx4uuH57f2AL8BfPrVlrs6xwsAAAAASUVORK5CYII=', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mpietrowskim', 'Murial Pietrowski', 'mpietrowskim@hibu.com', '$2a$12$JrfRlgEHwCaThx0Rrg70eea5M6WsIpZEdhLTPgmiAEQqaP.IThVPy', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAFFSURBVDjLfZNNasMwEEafHfcA7coUsigEssmZep2ue5VCobfIJgsbuklLS6DrLhJrvulClpF/0gExI8v6ZkZ6Kk6nk0siH2Y287vdrmDBKjOjruulNdwdgMPhwDWrJAGQ/HRzWZaY2XWB6WLamMdT8cUKAJ7ffobY3DEDNyHVvL5/eBCEYDw9PhSzCooifru/vcEdJJAck0dv0b9/hXkLTdPE0/Y7TOByJDAJM5AJ60XO3bjlarvdDuW8tN+eMsl82GQSMjA5XedjgXwSJCwIE1ifNQkl300ryCddJ7rQZ3Oh4ASlFuJthOC0besJsLHAxQihGmUcWupHMGez2QyAjQTOF9F8/qLQt2Px2mJFjguciHsCrMjBye14PPp6vR4gmgK2Wq3Y7/eU1wjLAVuiE5ifQW45YAmypX/+FRgA65/1NJbEH0d3cad+jVEKAAAAAElFTkSuQmCC', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('kpictonn', 'Kristian Picton', 'kpictonn@hatena.ne.jp', '$2a$12$90O6rm20N6fxDoS0sCfP3OnsDrdnQn4UHED.fF.HEmL19ryvWaAaS', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAE4SURBVDjLY/j//z8DJZhh8BhQXl5+oLi4+EBubu6BtLS0A/Hx8Qrh4eEH/Pz8Dri6uh4gaABQcwBQ84eUlJT/QM0TQGJAzQ1AzQtsbGwUiPIC0GYHoOb/kZGR/4GaC/DZjDMMgM6eEBgY+N/Nze0/0GYBkg0A2iwA0uzi4vLfyMhoAskGgJwNtLnA2tr6v4GBwX8FBQUHkHjIlAcKpaueX2jZ/PKDb9fdBgwDQDZDA6wAxNfU1JwAdMF/CQmJD4KCggbJ8x5vAGpU8Gq71dCw/vl/DAOgNh8AORuo2QBo8wGg5gNAzQe4uLgOsLCwGIDUJc56eCFl3qMHZCUk+4prDWGT7l0wz7lkQLIB1kVXApyqry0wybggYJh8wUEv/qwCSQZ4t948kD734f/kWQ/+h028+2HwZCYAjxChYziQ1VwAAAAASUVORK5CYII=', 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mbarbrooko', 'Maddie Barbrook', 'mbarbrooko@addthis.com', '$2a$12$jjE5U.ZnAqcDLt/T09J/G.JxQZyehlk8OTMZOqblhbZu1GBxz9E9C', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIwSURBVDjLhZHLaxNRGMUjaRDBjQtBxAdZFEQE/wEFUaurLm1FfEGzENwpturG6qIFrYUKXbUudOODNqIiTWqvFEwXKo1UUVRqS2NM0kmaZPKYPKbJ8XzTiUQxceDH3HvnO+e73xnH8X7fLjJInjbgEekiOwA4/sbBD0Ov5sIqY5SVXiO/Rpospw01HphXrOttZPBMxCkWJ3NltZItq3i2pOKZklrWi9Z5SMuKwf2GBtJVxJotiqWLKpIqqHCyYO3/Z/A8UyirBDtLcZTi6Y+RdxdHAsnTAy/NM0TerCuRlE2Y9El+YjCWoLBkViyxdL40OpNmLuBo0Gvk12AuYC5gLqB2XAw8A2NBFZzXVHm1YnHq1qQpYs4PjgbmAuYC5gLe0jrnWGLwzZqDi33ksSTunw3JvKZ0FbFmi5gLeDswF2v/h4Ftcm8yaIl9JMtcwFys4midOJQwEOX6ZyInBos18QYJk0yQVhJjLiiald/iTw+GMHN2N6YOuTB9YieCozfE4EvNYDO5Ttz2vn/Q+x5zC3EwEyw9GcaH7v0ovLiN6mcf8g8v4O35vRg+edTr+Ne/tU2OEV03SvB3uGFQjDvtQM8moM+N+M0D8B92LjQ0sE2+MhdMHXShOutF/ZO6toXnLdVm4o1yA1KYOLI+lrvbBVBU7HYgSZbOOeFvc4abGWwjXrLndefW3jeeVjPS44Z2xYXvnnVQ7S2rvjbn1aYj1BPo3H6ZHRfl2nz/ELGc/wJRo/MQHUFwBgAAAABJRU5ErkJggg==', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('kdarthep', 'Kathlin Darthe', 'kdarthep@nymag.com', '$2a$12$2J1gpdNZIdp6EFjSLdrcpOx7dqNYRPKJqvFtAdMyIJFl.4ILz1tpW', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAB+SURBVCjPpVHbCYAwDLyIIzmEX07hCH64nAOIuo1Sq+CZ1ueXVsyRBJrc5aBCPEeEvwuxK9XtDn0Si/ZU9gUg2Z/dYEuiuxSI5mRtwyuEIR5KOpVZYRUjjMLVVkIVCk6YPPdg1/LNQ87xdtl4JauaQ7CHjAfXeK5FH+7h9bNWB/9J3PASf8kAAAAASUVORK5CYII=', 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('kguiraudq', 'Krystal Guiraud', 'kguiraudq@topsy.com', '$2a$12$p0W6C5ueKUus1RWfdobUbuO8kchCKkjg.IeYMyNy9m0yLtlYAvuTe', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAH+SURBVBgZBcE9i11VGAbQtc/sO0OCkqhghEREAwpWAWUg8aMVf4KFaJEqQtAipTZWViKiCGOh2Ap2gmJhlSIWFsFOxUK0EsUM3pl79n4f12qHb3z3Fh7D83gC95GOJsDe0ixLk5Qq/+xv/Lw9Xd+78/HLX3Y8fXTr2nWapy4eCFKxG7Fby97SnDlYtMbxthyfzHO//nl85fNvfvnk8MbX5xa8IHx1518Vkrj54Q+qQms2vVmWZjdiu5ZR2rT01166/NCZg/2PFjwSVMU6yjoC1oq+x6Y3VbHdlXWExPd379nf7Nmejv2Os6OC2O4KLK0RNn3RNCdr2Z5GJSpU4o+/TkhaJ30mEk5HwNuvX7Hpi76wzvjvtIwqVUSkyjqmpHS0mki8+9mPWmuWxqYvGkbFGCUAOH/+QevYI9GFSqmaHr5wkUYTAlGhqiRRiaqiNes6SOkwJwnQEqBRRRJEgkRLJGVdm6R0GLMQENE0EkmkSkQSVVMqopyuIaUTs0J455VLAAAAAODW0U/GiKT0pTWziEj44PZ1AAAAcPPqkTmH3QiJrlEVDXDt0qsAAAAAapa5BqUnyaw0Am7//gUAAAB49tEXzTmtM5KkV/y2G/X4M5fPao03n/sUAAAAwIX7y5yBv9vhjW/fT/IkuSp5gJKElKRISYoUiSRIyD1tufs/IXxui20QsKIAAAAASUVORK5CYII=', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mpillingtonr', 'Moses Pillington', 'mpillingtonr@ucoz.ru', '$2a$12$3mdQpI.u5ZKuQxbiG.kb4.B4fSVp08ZFvbE1imHJWjKzfYu7FgTHK', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAKZSURBVDjLhVPfa1JRAL5PPfTWyx7WS/QURA/9AREVRGMPvTSIRu1hLBq0oJdWK9hDWFshVDps0dWm06FzlplFP3abtiVOJlOnE51Tp+E2NvMHd2pX93XOYY0wRwc+OHz3fD/OOfdwALh6OJ3OBzabLa3Varcp6Jxyjdb+Q0xMTDQZjcZfqBuUMxgMzQ0NdhLjJK1GAKvVilAohFqthmq1ikAgwDj6ja6ha/80YgbEvfx3WiKRgNlshs/nYxgfH2dcXaMyM7BYLBoCBINBlkjhcrlgt9uh0+kY6JxytI0kSfD7/aAanucNHEmSwuEw5ubm4PV6WSJNrx+UC37nEdZfRNQqwzx/Fq+Hb1Y5Ui9XLBaxtraGWCyG5eVlkP0hEonsNqJz79dHyAi9yPnfAcUf+Ok345v8/DZnMpmYQT6fRzabRTKZZHU9Hs/uGQSmFdhc6Ecl9RaZqcfYClhQTUzDp7nGDpAZ5HI5bGxsYH19Hel0mqWyrU2pkF24h9rWNLZi17E524NFXScRd+GVSl7gyN1WRFFEoVBAJpNh4ng8jmg0irBHi2zgDmolF8SlDpRW2lEI9WHpZStmhTcYGhoqc3q9/rMgCEilUmwb1ISehXdyGBlP7474CkrJS8gv3EX0eQtm7KMgwVAqlQ6OXNM+8nN0jYyMRB0OB1ZXVxFxjyH08Rap7UQp3onyymXkSJOI6hysYzwVJgh6FArF/t1fUqPRNKnVahk/0C0G3/fB9+EJ0u42iPF2bM7fxqKqFWrlgEiEciI8uOdbMA2eAQoJhF9cgNDXjBlSWZCdhO5Z/yciPv7fx3S/66hUcSlRcT+FW3YKxquHqqOD3TcavcSGBm0nDpQedhyRJgdapC/y0w5b77HDe4kpfgO2GyDntdvjkwAAAABJRU5ErkJggg==', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('dclemensens', 'Davine Clemensen', 'dclemensens@sina.com.cn', '$2a$12$q5FruFlJfr1T0top08UHg.Vr9hZSJ2ZjGy1SHCC8yeEfxVPHfm2SG', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIvSURBVDjLpVNNi1JRGH7OdfKaH5MTQkFhYeCiYTDaSAs3SVJQOxcFLf0TguAfCFwHQdt+gcPsIgcXxYiXXGQKxWSMFor5/XXv6X2P3stABkEHDu895zzv8z7Pe88RUkr8z9g5v6hUKq9Xq9Xz5XK5QxEU1VwsFhzfpNPpZ1sJDMO4RAmvgsFgWte9mFMSLAtKGync2wvi6OjwKa3+JKhWqw85ORQKXfP5fPjy9Tu4umVJWNIiIgmTyObz+XYLBD4Mh8NqMZ3OsKTkj8YJEVjOfJB6pGxsJWCPbrcbg8FgXdk0cXv/jqouN9W50X9VwMwmJTGIfwhLN6ofKJpqv9VqwePxKHA2myVPENwZsnsll8v91FjBuldSJTDB/sFd7O5eRqPRwGQyQa/Xw3A4RCQSEWT3CZNMp9OrjgKbQJFQZZZuGBXE43GMx2OnF7VaTUaj0V+MJ7vSIXCSzTWQCTRNQNd1+P1+dRdYwSZxuYnrHnBz7NvIjWMLctM4BnFz7UbzoG/BVm3lSgEfulwuVdkyOdmywUoFV2dl9h4T2Ao0UvC+VCphNBohEAjAc1GnGygcVf1+n+xoEEKoNcULRCBns5kCaJlMJt5sNh8Xi8WT4+N30N0CN29cx/1kCp/qn3F21sbp6TfU63X8oOH1et+SCpOUKQni/GvM5/MpOnxB88B+TByTySSoIsrlskkwF2MTiYRSIf7lORcKBbPdbqPb7d6jpFu05Y/FYi87nY78DZN2pgrwMw41AAAAAElFTkSuQmCC', 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('radamskit', 'Roddie Adamski', 'radamskit@opensource.org', '$2a$12$TVx9ET0bVi/nFgkDSM5cGeg8s3GyE8yVtjeJ3p2CFPaHF6dlqgVna', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAANCSURBVBgZBcHbT1t1AMDx72lPKS29UCiz0BUMQxwwJptMSIAZzRgu6oOJ+jKjkQdjTAx/gI9mezAmJkb3ppKYqHsxe9oMOh0ZODd3xYVtFOLK1dG0pYWensvv4udjaK0BAACYmp8cAz4GjgEtgAmUgeta6XNfjn33CwAAgKG1BmBqftIEpoE3X8+cCCZCLVSsBiwXhLQRPOHy1iUhhfxVCPn2N6d+2gMwtNZMzU8GgD8Gk30jJzMvUbGDOLgsVwzqdJCCpdDCJYTFlnOVm5s3F4Qnjv/w1oWyDwCYPtrcPTLaNkhRung+AyF81EQdFnUUnSDbdoj1coD2yAsMpp497DrejwD+0vjqKPDZ6e7X/PdllS1q1JRgz45QdAJUbMhu7FKuVgkmChjxLMPJg1xevNH5/fXpe/6hySNfTLQNHTL8IbZ8AvQ+WmWEW0/81Gwfixt7qPoSwY5HOLEseVXCLEkONWd8tx4/bDKBY5lYmrvWJvl6H73+AygEuW0X264RT2kqTTMsqx1wNI0iSDbvcOLpo3iO6DeB5rDZQM7aZNuxiIY72XGjlEqKeIvNvoRFXg6QvnMOaVfJZw5S3AkTCUXxXNHo01obhgbXqaCtVkxPcukvD6M+xNayydpqjDYnhPA0+5M9BJfv4Nk10BohhGFKoYoVt5Ju9jcSrX+O9byJ7QVoVR8RD0ucDY/dnCDd1EVPaohdu8rC+u8UqxNIocqm8MTtx8XVdFc4w2//zdMY7qLOn0Eol/G+95BaIZVEodksr9G/f4Q9t8YnFz4Euh/4PFd89fPDWdERacG0NigX/iSRcLCFi9SKXCHLv4UlVvKL7NQK5IorDGTGeCb1PLuBe6O+b189P+M63sWZxVleTA8Q9zeQiChsYSOk4KlYO6lYB63xTgL+EC3RNLfX5rm2csOyXGImgOd471zJ3p1zau7hiSPHebRt8o9wmL72Oa5ysYXLgWQvw50n+Ts3x5WlWScs23uWz2ZrhtYagFe+fjkqPHFeeHL83ZH3TWQKrcMYPoNkvMKnF0/T1zrM1aW53Qbd3rtwZmkdwNBaAwAAMHJm6A0p5AdSqn4lVQIAKO/47yeFIlBTMrB9VgsAgP8BON24AjtZfcoAAAAASUVORK5CYII=', 'Active', 'Organizer');

insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('admin', 'Administrator', 'admin@evup.com', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJ6SURBVDjLjZO7T1NhGMY7Mji6uJgYt8bElTjof6CDg4sMSqIxJsRGB5F4TwQSIg1QKC0KWmkZEEsKtEcSxF5ohV5pKSicXqX3aqGn957z+PUEGopiGJ583/A+v3znvPkJAAjWR0VNJG0kGhKahCFhXcN3YBFfx8Kry6ym4xIzce88/fbWGY2k5WRb77UTTbWuYA9gDGg7EVmSIOF4g5T7HZKuMcSW5djWDyL0uRf0dCc8inYYxTcw9fAiCMBYB3gVj1z7gLhNTjKCqHkYP79KENC9Bq3uxrrqORzy+9D3tPAAccspVx1gWg0KbaZFbGllWFM+xrKkFQudV0CeDfJsjN4+C2nracjunoPq5VXIBrowMK4V1gG1LGyWdbZwCalsBYUyh2KFQzpXxVqkAGswD3+qBDpZwow9iYE5v26/VwfUQnnznyhvjguQYabIIpKpYD1ahI8UTT92MUSFuP5Z/9TBTgOgFrVjp3nakaG/0VmEfpX58pwzjUEquNk362s+PP8XYD/KpYTBHmRg9Wch0QX1R80dCZhYipudYQY2Auib8RmODVCa4hfUK4ngaiiLNFNFdKeCWWscXZMbWy9Unv9/gsIQU09a4pwvUeA3Uapy2C2wCKXL0DqTePLexbWPOv79E8f0UWrencZ2poxciUWZlKssB4bcHeE83NsFuMgpo2iIpMuNa1TNu4XjhggWvb+R2K3wZdLlAZl8Fd9jRb5sD+Xx0RJBx5gdom6VsMEFDyWF0WyCeSOFcDKPnRxZYTQL5Rc/nn1w4oFsBaIhC3r6FRh5erPRhYMyHdeFw4C6zkRhmijM7CnMu0AUZonCDCnRJBqSus5/ABD6Ba5CkQS8AAAAAElFTkSuQmCC', 'Active', 'Admin');
insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('organizer', 'Organizer', 'organizer@evup.com', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJ6SURBVDjLjZO7T1NhGMY7Mji6uJgYt8bElTjof6CDg4sMSqIxJsRGB5F4TwQSIg1QKC0KWmkZEEsKtEcSxF5ohV5pKSicXqX3aqGn957z+PUEGopiGJ583/A+v3znvPkJAAjWR0VNJG0kGhKahCFhXcN3YBFfx8Kry6ym4xIzce88/fbWGY2k5WRb77UTTbWuYA9gDGg7EVmSIOF4g5T7HZKuMcSW5djWDyL0uRf0dCc8inYYxTcw9fAiCMBYB3gVj1z7gLhNTjKCqHkYP79KENC9Bq3uxrrqORzy+9D3tPAAccspVx1gWg0KbaZFbGllWFM+xrKkFQudV0CeDfJsjN4+C2nracjunoPq5VXIBrowMK4V1gG1LGyWdbZwCalsBYUyh2KFQzpXxVqkAGswD3+qBDpZwow9iYE5v26/VwfUQnnznyhvjguQYabIIpKpYD1ahI8UTT92MUSFuP5Z/9TBTgOgFrVjp3nakaG/0VmEfpX58pwzjUEquNk362s+PP8XYD/KpYTBHmRg9Wch0QX1R80dCZhYipudYQY2Auib8RmODVCa4hfUK4ngaiiLNFNFdKeCWWscXZMbWy9Unv9/gsIQU09a4pwvUeA3Uapy2C2wCKXL0DqTePLexbWPOv79E8f0UWrencZ2poxciUWZlKssB4bcHeE83NsFuMgpo2iIpMuNa1TNu4XjhggWvb+R2K3wZdLlAZl8Fd9jRb5sD+Xx0RJBx5gdom6VsMEFDyWF0WyCeSOFcDKPnRxZYTQL5Rc/nn1w4oFsBaIhC3r6FRh5erPRhYMyHdeFw4C6zkRhmijM7CnMu0AUZonCDCnRJBqSus5/ABD6Ba5CkQS8AAAAAElFTkSuQmCC', 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('user', 'User', 'user@evup.com', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJ6SURBVDjLjZO7T1NhGMY7Mji6uJgYt8bElTjof6CDg4sMSqIxJsRGB5F4TwQSIg1QKC0KWmkZEEsKtEcSxF5ohV5pKSicXqX3aqGn957z+PUEGopiGJ583/A+v3znvPkJAAjWR0VNJG0kGhKahCFhXcN3YBFfx8Kry6ym4xIzce88/fbWGY2k5WRb77UTTbWuYA9gDGg7EVmSIOF4g5T7HZKuMcSW5djWDyL0uRf0dCc8inYYxTcw9fAiCMBYB3gVj1z7gLhNTjKCqHkYP79KENC9Bq3uxrrqORzy+9D3tPAAccspVx1gWg0KbaZFbGllWFM+xrKkFQudV0CeDfJsjN4+C2nracjunoPq5VXIBrowMK4V1gG1LGyWdbZwCalsBYUyh2KFQzpXxVqkAGswD3+qBDpZwow9iYE5v26/VwfUQnnznyhvjguQYabIIpKpYD1ahI8UTT92MUSFuP5Z/9TBTgOgFrVjp3nakaG/0VmEfpX58pwzjUEquNk362s+PP8XYD/KpYTBHmRg9Wch0QX1R80dCZhYipudYQY2Auib8RmODVCa4hfUK4ngaiiLNFNFdKeCWWscXZMbWy9Unv9/gsIQU09a4pwvUeA3Uapy2C2wCKXL0DqTePLexbWPOv79E8f0UWrencZ2poxciUWZlKssB4bcHeE83NsFuMgpo2iIpMuNa1TNu4XjhggWvb+R2K3wZdLlAZl8Fd9jRb5sD+Xx0RJBx5gdom6VsMEFDyWF0WyCeSOFcDKPnRxZYTQL5Rc/nn1w4oFsBaIhC3r6FRh5erPRhYMyHdeFw4C6zkRhmijM7CnMu0AUZonCDCnRJBqSus5/ABD6Ba5CkQS8AAAAAElFTkSuQmCC', 'Active', 'User');
insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('password', 'Password Test', 'cisat14362@nubotel.com', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJ6SURBVDjLjZO7T1NhGMY7Mji6uJgYt8bElTjof6CDg4sMSqIxJsRGB5F4TwQSIg1QKC0KWmkZEEsKtEcSxF5ohV5pKSicXqX3aqGn957z+PUEGopiGJ583/A+v3znvPkJAAjWR0VNJG0kGhKahCFhXcN3YBFfx8Kry6ym4xIzce88/fbWGY2k5WRb77UTTbWuYA9gDGg7EVmSIOF4g5T7HZKuMcSW5djWDyL0uRf0dCc8inYYxTcw9fAiCMBYB3gVj1z7gLhNTjKCqHkYP79KENC9Bq3uxrrqORzy+9D3tPAAccspVx1gWg0KbaZFbGllWFM+xrKkFQudV0CeDfJsjN4+C2nracjunoPq5VXIBrowMK4V1gG1LGyWdbZwCalsBYUyh2KFQzpXxVqkAGswD3+qBDpZwow9iYE5v26/VwfUQnnznyhvjguQYabIIpKpYD1ahI8UTT92MUSFuP5Z/9TBTgOgFrVjp3nakaG/0VmEfpX58pwzjUEquNk362s+PP8XYD/KpYTBHmRg9Wch0QX1R80dCZhYipudYQY2Auib8RmODVCa4hfUK4ngaiiLNFNFdKeCWWscXZMbWy9Unv9/gsIQU09a4pwvUeA3Uapy2C2wCKXL0DqTePLexbWPOv79E8f0UWrencZ2poxciUWZlKssB4bcHeE83NsFuMgpo2iIpMuNa1TNu4XjhggWvb+R2K3wZdLlAZl8Fd9jRb5sD+Xx0RJBx5gdom6VsMEFDyWF0WyCeSOFcDKPnRxZYTQL5Rc/nn1w4oFsBaIhC3r6FRh5erPRhYMyHdeFw4C6zkRhmijM7CnMu0AUZonCDCnRJBqSus5/ABD6Ba5CkQS8AAAAAElFTkSuQmCC', 'Active', 'User');



-- event --
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 2, 'Kovacek-Conn', false, '16 Brentwood Park', 'Nondisplaced fracture of base of neck of left femur', 'http://dummyimage.com/214x100.png/dddddd/000000', '2023-11-08', '2023-11-30');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 13, 'Wilkinson-Klein', false, '21812 Ohio Alley', 'Benign neoplasm of scrotum', 'http://dummyimage.com/136x100.png/dddddd/000000', '2023-02-06', '2023-02-16');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 17, 'McDermott, Hammes and Medhurst', true, '52 Onsgard Trail', 'Other injury of superior mesenteric artery', 'http://dummyimage.com/109x100.png/dddddd/000000', '2023-01-03', '2023-01-31');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 18, 'Marquardt and Sons', false, '0752 Mayfield Park', 'Nondisplaced fracture of distal phalanx of right ring finger, sequela', 'http://dummyimage.com/134x100.png/cc0000/ffffff', '2023-01-24', '2023-01-25');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 20, 'Rutherford, DuBuque and MacGyver', false, '3926 Montana Avenue', 'Person boarding or alighting a three-wheeled motor vehicle injured in collision with two- or three-wheeled motor vehicle', 'http://dummyimage.com/175x100.png/ff4444/ffffff', '2023-01-03', '2023-02-15');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 21, 'Mohr, Cummerata and Rempel', false, '902 Springview Center', 'Unspecified intracranial injury with loss of consciousness of 1 hour to 5 hours 59 minutes, sequela', 'http://dummyimage.com/155x100.png/cc0000/ffffff', '2023-12-02', '2023-12-03');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 22, 'Trantow-Zieme', false, '96 Sauthoff Center', 'Corrosion of unspecified degree of multiple right fingers (nail), not including thumb, initial encounter', 'http://dummyimage.com/238x100.png/5fa2dd/ffffff', '2022-11-29', '2022-12-29');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 28, 'Beahan, Brakus and Schultz', true, '5 1st Circle', 'Displaced fracture of fifth metatarsal bone, left foot, subsequent encounter for fracture with nonunion', 'http://dummyimage.com/246x100.png/ff4444/ffffff', '2023-01-03', '2023-02-17');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 30, 'Stiedemann, Heidenreich and Bradtke', false, '73 Miller Terrace', 'Other specified and unspecified injuries of neck', 'http://dummyimage.com/205x100.png/cc0000/ffffff', '2023-01-19', '2023-01-24');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 2, 'Lesch Inc', false, '94086 Vahlen Avenue', 'Breakdown (mechanical) of surgically created arteriovenous fistula, subsequent encounter', 'http://dummyimage.com/222x100.png/ff4444/ffffff', '2023-01-02', '2023-01-03');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 17, 'Haag, Reinger and Hegmann', true, '61 Forest Park', 'Periprosthetic osteolysis of internal prosthetic right knee joint, sequela', 'http://dummyimage.com/211x100.png/5fa2dd/ffffff', '2022-11-29', '2023-01-01');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 18, 'Reynolds, Mertz and Adams', false, '18331 Bonner Alley', 'Other superficial bite of right upper arm, initial encounter', 'http://dummyimage.com/248x100.png/ff4444/ffffff', '2022-12-08', '2022-12-09');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 20, 'Bauch, Mitchell and Mraz', true, '583 Heffernan Junction', 'Ataxia following unspecified cerebrovascular disease', 'http://dummyimage.com/221x100.png/dddddd/000000', '2023-02-26', '2023-02-27');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 21, 'Leannon and Sons', true, '4626 Claremont Junction', 'Type AB blood, Rh positive', 'http://dummyimage.com/219x100.png/ff4444/ffffff', '2023-01-27', '2023-01-30');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 22, 'Lebsack, Hickle and Kassulke', false, '689 Debs Point', 'Other cyst of bone, left hand', 'http://dummyimage.com/234x100.png/5fa2dd/ffffff', '2022-12-23', '2022-12-25');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 28, 'Raynor, Durgan and Pfeffer', false, '5137 Bonner Junction', 'Diabetes mellitus due to underlying condition with proliferative diabetic retinopathy with combined traction retinal detachment and rhegmatogenous retinal detachment, unspecified eye', 'http://dummyimage.com/197x100.png/ff4444/ffffff', '2022-12-01', '2022-12-15');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 30, 'Lehner-Yundt', false, '765 Prairie Rose Hill', 'Maternal care for other malpresentation of fetus', 'http://dummyimage.com/179x100.png/dddddd/000000', '2022-12-29', '2023-01-27');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 2, 'Bauch-Pagac', true, '6762 Mallard Point', 'Laceration of unspecified urinary and pelvic organ, sequela', 'http://dummyimage.com/114x100.png/cc0000/ffffff', '2023-01-04', '2023-01-30');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 13, 'Hermann-Stamm', true, '479 Duke Drive', 'Unspecified fracture of shaft of unspecified tibia, initial encounter for open fracture type IIIA, IIIB, or IIIC', 'http://dummyimage.com/200x100.png/dddddd/000000', '2023-03-09', '2023-03-12');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 17, 'Lowe, Renner and Hand', true, '726 Mallard Junction', 'Nondisplaced fracture of lower epiphysis (separation) of unspecified femur, initial encounter for open fracture type I or II', 'http://dummyimage.com/166x100.png/cc0000/ffffff', '2022-12-16', '2023-02-02');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 18, 'Kreiger-Leuschke', true, '7163 Maryland Park', 'Laceration of other blood vessels of thorax, left side, initial encounter', 'http://dummyimage.com/217x100.png/dddddd/000000', '2023-02-08', '2023-02-17');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 20, 'Schneider and Sons', true, '498 Miller Trail', 'Pedestrian injured in unspecified nontraffic accident', 'http://dummyimage.com/243x100.png/ff4444/ffffff', '2022-11-21', '2022-11-29');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 21, 'pollich LLC', false, '280 Banding Parkway', 'Activities involving personal hygiene and interior property and clothing maintenance', 'http://dummyimage.com/186x100.png/ff4444/ffffff', '2023-10-25', '2023-10-30');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 22, 'Brown LLC', true, '3548 Crest Line Plaza', 'Adverse effect of predominantly alpha-adrenoreceptor agonists', 'http://dummyimage.com/198x100.png/dddddd/000000', '2022-12-02', '2022-12-16');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 28, 'Hermiston and Sons', true, '06 Summerview Lane', 'Sepsis due to Methicillin resistant Staphylococcus aureus', 'http://dummyimage.com/168x100.png/5fa2dd/ffffff', '2023-01-17', '2023-01-18');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 30, 'Ratke-Conn', true, '5 Dovetail Park', 'Person boarding or alighting from bus injured in collision with fixed or stationary object', 'http://dummyimage.com/160x100.png/cc0000/ffffff', '2023-11-17', '2023-12-06');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 2, 'Green, Walter and Boyle', true, '81 Upham Road', 'Displaced supracondylar fracture with intracondylar extension of lower end of unspecified femur, subsequent encounter for open fracture type IIIA, IIIB, or IIIC with malunion', 'http://dummyimage.com/159x100.png/dddddd/000000', '2022-11-11', '2022-11-21');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 13, 'Sauer, Gerlach and Kiehn', true, '002 Lindbergh Center', 'Asphyxiation due to plastic bag, accidental, sequela', 'http://dummyimage.com/212x100.png/ff4444/ffffff', '2023-12-01', '2023-12-15');
insert into event ( userid, eventname, public, eventaddress, description, eventcanceled, eventphoto, startdate, enddate) values ( 17, 'Graham-Lemke', true, '7585 Oriole Terrace', 'Flaccid hemiplegia', false, 'http://dummyimage.com/212x100.png/ff4444/ffffff', '2023-01-06', '2023-01-08');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 32, 'Christmas Party', true, '001 North Pole', 'Christmas Party in Santa house. Everyone can join!', 'http://dummyimage.com/212x100.png/ff4444/ffffff', '2022-12-24', '2022-12-25');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 13, 'Jerde Inc', false, '519 Arapahoe Parkway', 'Unspecified parasitic disease', 'http://dummyimage.com/103x100.png/5fa2dd/ffffff', '2022-12-19', '2023-02-13');
-- attendee --

insert into attendee (attendeeid, eventid) values (1, 1);
insert into attendee (attendeeid, eventid) values (1, 10);
insert into attendee (attendeeid, eventid) values (1, 11);
insert into attendee (attendeeid, eventid) values (2, 1);
insert into attendee (attendeeid, eventid) values (2, 2);
insert into attendee (attendeeid, eventid) values (3, 1);
insert into attendee (attendeeid, eventid) values (3, 3);
insert into attendee (attendeeid, eventid) values (3, 13);
insert into attendee (attendeeid, eventid) values (4, 14);
insert into attendee (attendeeid, eventid) values (4, 4);
insert into attendee (attendeeid, eventid) values (5, 5);
insert into attendee (attendeeid, eventid) values (5, 15);
insert into attendee (attendeeid, eventid) values (7, 7);
insert into attendee (attendeeid, eventid) values (8, 8);
insert into attendee (attendeeid, eventid) values (9, 9);
insert into attendee (attendeeid, eventid) values (1, 30);
insert into attendee (attendeeid, eventid) values (4, 30);
insert into attendee (attendeeid, eventid) values (6, 30);
insert into attendee (attendeeid, eventid) values (12, 30);
insert into attendee (attendeeid, eventid) values (32, 2);
insert into attendee (attendeeid, eventid) values (32, 7);
insert into attendee (attendeeid, eventid) values (32, 8);
insert into attendee (attendeeid, eventid) values (32, 15);

-- Category --

insert into Category (categoryid, categoryname) values (1, 'Tudo');
insert into Category (categoryid, categoryname) values (2, 'Cinema');
insert into Category (categoryid, categoryname) values (3, 'Ar livre');
insert into Category (categoryid, categoryname) values (4, 'Música');
insert into Category (categoryid, categoryname) values (5, 'Família');
insert into Category (categoryid, categoryname) values (6, 'Exposição');
insert into Category (categoryid, categoryname) values (7, 'Literatura');
insert into Category (categoryid, categoryname) values (8, 'Conferência');
insert into Category (categoryid, categoryname) values (9, 'Congresso');
insert into Category (categoryid, categoryname) values (10, 'Seminário');
insert into Category (categoryid, categoryname) values (11, 'Encontro');
insert into Category (categoryid, categoryname) values (12, 'Online');
insert into Category (categoryid, categoryname) values (13, 'Palestra');
insert into Category (categoryid, categoryname) values (14, 'Teatro');
insert into Category (categoryid, categoryname) values (15, 'Desporto');

-- tag --

insert into tag (tagid, tagname) values (1, 'Tudo');
insert into tag (tagid, tagname) values (2, 'Reitoria');
insert into tag (tagid, tagname) values (3, 'ICBAS');
insert into tag (tagid, tagname) values (4, 'FMUP');
insert into tag (tagid, tagname) values (5, 'Porto');
insert into tag (tagid, tagname) values (6, 'FCUP');
insert into tag (tagid, tagname) values (7, 'INESC TEC');
insert into tag (tagid, tagname) values (8, 'FLUP');
insert into tag (tagid, tagname) values (9, 'Sociologia');
insert into tag (tagid, tagname) values (10, 'Concerto');
insert into tag (tagid, tagname) values (11, 'Solidariedade');
insert into tag (tagid, tagname) values (12, 'Arte');
insert into tag (tagid, tagname) values (13, 'Cinema');
insert into tag (tagid, tagname) values (14, 'Literatura');
insert into tag (tagid, tagname) values (15, 'Museologia');

-- Report --

insert into Report (reportId, reporterId, eventid, message, reportstatus) values (1, 1, 1, 'This event is not suitable for up students.', false);
insert into Report (reportId, reporterId, eventid, message, reportstatus) values (2, 3, 22, 'This event is abusive.', true);
insert into Report (reportId, reporterId, eventid, message, reportstatus) values (3, 2, 3, 'The organizer of this event was rude to me.', false);
insert into Report (reportId, reporterId, eventid, message, reportstatus) values (4, 3, 1, 'This is spam!', false);
insert into Report (reportId, reporterId, eventid, message, reportstatus) values (5, 1, 21, 'Wrong category', true);
insert into Report (reportId, reporterId, eventid, message, reportstatus) values (6, 1, 23, 'The event image is inappropriate...', true);
insert into Report (reportId, reporterId, eventid, message, reportstatus) values (7, 5, 28, 'Fraud', false);
insert into Report (reportId, reporterId, eventid, message, reportstatus) values (8, 3, 1, 'Should be tagged as adult content', true);
insert into Report (reportId, reporterId, eventid, message, reportstatus) values (9, 6, 30, 'Should be tagged as adult content', true);

--invitation --

insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (4, 8, 1, null);
insert into invitation (inviterId, inviteeId, eventid, invitationstatus) values (4, 7, 2, null);
insert into invitation (inviterId, inviteeId, eventid, invitationstatus) values (7, 4, 3, null);
insert into invitation (inviterId, inviteeId, eventid, invitationstatus) values (9, 3, 4, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (1, 2, 5, true);
insert into invitation (inviterId, inviteeId, eventid, invitationstatus) values (1, 4, 6, true);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (2, 22, 7, false);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (3, 22, 8, true);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (4, 3, 8, false);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (3, 8, 9, false);

insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (2, 1, 2, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (3, 1, 3, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (2, 1, 3, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (3, 1, 2, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (2, 1, 5, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (3, 1, 8, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (2, 1, 2, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (3, 1, 4, null);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (4, 1, 4, false);
insert into invitation ( inviterId, inviteeId, eventid, invitationstatus) values (3, 1, 1, false);

-- poll --

insert into poll (pollid, eventid, pollcontent) values (1, 4,'What topics interest you the most?');
insert into poll (pollid, eventid, pollcontent) values (2, 30,'What day are you going to the event?');
insert into poll (pollid, eventid, pollcontent) values (3, 19,'What are you most looking forward to during the event?');
insert into poll (pollid, eventid, pollcontent) values (4, 26,'What place should we have our next event in?');
insert into poll (pollid, eventid, pollcontent) values (5, 18,'Are you interested in a meet-up after the event for further discussion?');
insert into poll (pollid, eventid, pollcontent) values (6, 6, 'Who are you going to the event with?');
insert into poll (pollid, eventid, pollcontent) values (7, 5, 'Your reasons for attending this event:');
insert into poll (pollid, eventid, pollcontent) values (8, 7, 'What made you decide to attend this event?');
insert into poll (pollid, eventid, pollcontent) values (9, 27, 'Were you able to connect with all of the things you wanted to during the event?');
insert into poll (pollid, eventid, pollcontent) values (10, 7, 'How useful will the topics covered be to you in your course?');

-- comment --

insert into comment (authorId, eventid, commentcontent, commentdate) values (4, 4, 'I am looking forward to it!!', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (8, 8, 'I have some questions about the event. Someone can help me?', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (7, 7, 'Looks very useful', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (2, 1, ':)', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (5, 5, ':(', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (1, 1, 'It was fun', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (9, 9, 'This event changed my life!', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (2, 2, 'I did not like the event. I am disappointed.', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (7, 7, 'Where is the event?', CURRENT_TIMESTAMP);
insert into comment (authorId, eventid, commentcontent, commentdate) values (2, 1, 'Nice!', CURRENT_TIMESTAMP);

-- joinrequest --

insert into joinrequest ( requesterid, eventid, requeststatus) values ( 1, 29, true);
insert into joinrequest ( requesterid, eventid, requeststatus) values ( 4, 10, false);
insert into joinrequest ( requesterid, eventid, requeststatus) values ( 4, 16, false);
insert into joinrequest ( requesterid, eventid, requeststatus) values ( 2, 9, false);
insert into joinrequest ( requesterid, eventid, requeststatus) values ( 1, 10, true);
insert into joinrequest ( requesterid, eventid, requeststatus) values ( 2, 12, false);
insert into joinrequest ( requesterid, eventid, requeststatus) values ( 3, 2, true);
insert into joinrequest ( requesterid, eventid, requeststatus) values ( 1, 17, true);
insert into joinrequest ( requesterid, eventid, requeststatus) values ( 3, 23, true);
insert into joinrequest ( requesterid, eventid) values ( 1, 9);

-- organizerrequest --

insert into organizerrequest ( requesterid, requeststatus) values ( 5, false);
insert into organizerrequest ( requesterid, requeststatus) values ( 9, true);
insert into organizerrequest ( requesterid, requeststatus) values ( 4, true);
insert into organizerrequest ( requesterid) values (8);

-- notification --

insert into notification ( receiverid, eventid, notificationdate, notificationtype, notificationstatus) values (1, 5, CURRENT_TIMESTAMP, 'EventChange', false);
insert into notification ( receiverid, joinrequestid, notificationdate, notificationtype, notificationstatus) values ( 1, 7, CURRENT_TIMESTAMP, 'JoinRequestReviewed', true);
insert into notification ( receiverid, organizerrequestid, notificationdate, notificationtype, notificationstatus) values ( 8, 2, CURRENT_TIMESTAMP, 'OrganizerRequestReviewed', true);
insert into notification ( receiverid, invitationid, notificationdate, notificationtype, notificationstatus) values ( 9, 9, CURRENT_TIMESTAMP, 'InviteReceived', true);
insert into notification ( receiverid, invitationid, notificationdate, notificationtype, notificationstatus) values ( 4, 3, CURRENT_TIMESTAMP, 'InviteAccepted', false);
insert into notification ( receiverid, pollid, notificationdate, notificationtype, notificationstatus) values ( 3, 9, CURRENT_TIMESTAMP, 'NewPoll', false);

-- vote --

insert into vote (voterid, commentid, type) values (1, 7, false);
insert into vote (voterid, commentid, type) values (2, 3, true);
insert into vote (voterid, commentid, type) values (3, 7, true);
insert into vote (voterid, commentid, type) values (4, 9, false);
insert into vote (voterid, commentid, type) values (5, 3, false);
insert into vote (voterid, commentid, type) values (6, 7, false);
insert into vote (voterid, commentid, type) values (7, 5, true);
insert into vote (voterid, commentid, type) values (8, 3, true);
insert into vote (voterid, commentid, type) values (9, 1, false);
insert into vote (voterid, commentid, type) values (10, 9, false);
insert into vote (voterid, commentid, type) values (11, 2, true);
insert into vote (voterid, commentid, type) values (12, 3, false);
insert into vote (voterid, commentid, type) values (13, 3, true);
insert into vote (voterid, commentid, type) values (14, 9, false);
insert into vote (voterid, commentid, type) values (15, 7, true);

-- polloption --
insert into polloption (polloptionid, optioncontent) values (1, 'Yes');
insert into polloption (polloptionid, optioncontent) values (2, 'No');
insert into polloption (polloptionid, optioncontent) values (3, 'Not at all useful');
insert into polloption (polloptionid, optioncontent) values (4, 'Somewhat useful');
insert into polloption (polloptionid, optioncontent) values (5, 'Useful');
insert into polloption (polloptionid, optioncontent) values (6, 'Very useful');
insert into polloption (polloptionid, optioncontent) values (7, 'Friend(s)');
insert into polloption (polloptionid, optioncontent) values (8, 'Family');
insert into polloption (polloptionid, optioncontent) values (9, '10');
insert into polloption (polloptionid, optioncontent) values (10, '11');
insert into polloption (polloptionid, optioncontent) values (11, '12');
insert into polloption (polloptionid, optioncontent) values (12, '13');
insert into polloption (polloptionid, optioncontent) values (13, '14');
insert into polloption (polloptionid, optioncontent) values (14, 'Other');
insert into polloption (polloptionid, optioncontent) values (15, 'Have fun with friends');
insert into polloption (polloptionid, optioncontent) values (16, 'Meet new people');
insert into polloption (polloptionid, optioncontent) values (17, 'Can be useful for university subjects');
insert into polloption (polloptionid, optioncontent) values (18, 'FEUP');
insert into polloption (polloptionid, optioncontent) values (19, 'FCUP');
insert into polloption (polloptionid, optioncontent) values (20, 'FCUL');
insert into polloption (polloptionid, optioncontent) values (21, 'Computing in the Modern World');
insert into polloption (polloptionid, optioncontent) values (22, 'UNIX/LINUX Fundamentals');
insert into polloption (polloptionid, optioncontent) values (23, 'Introduction to Software Engineering.');
insert into polloption (polloptionid, optioncontent) values (24, 'Operating Systems');
insert into polloption (polloptionid, optioncontent) values (25, 'FMUP');
insert into polloption (polloptionid, optioncontent) values (26, 'Maybe');
insert into polloption (polloptionid, optioncontent) values (27, 'ICABAS');
insert into polloption (polloptionid, optioncontent) values (28, 'None of the options');
insert into polloption (polloptionid, optioncontent) values (29, 'Some');
insert into polloption (polloptionid, optioncontent) values (30, 'All');


-- answer --                            voteType??

insert into answer (userid, pollid) values (9, 2);
insert into answer (userid, pollid) values (7, 2);
insert into answer (userid, pollid) values (2, 3);
insert into answer (userid, pollid) values (4, 1);
insert into answer (userid, pollid) values (5, 9);
insert into answer (userid, pollid) values (4, 6);
insert into answer (userid, pollid) values (2, 1);
insert into answer (userid, pollid) values (8, 10);
insert into answer (userid, pollid) values (1, 5);
insert into answer (userid, pollid) values (2, 7);
insert into answer (userid, pollid) values (1, 9);
insert into answer (userid, pollid) values (1, 2);
insert into answer (userid, pollid) values (9, 8);
insert into answer (userid, pollid) values (2, 4);
insert into answer (userid, pollid) values (3, 3);

-- upload --

insert into upload (uploadid, commentid, fileName) values (1, 1, 'http://dummyimage.com/182x100.png/ff4444/ffffff');
insert into upload (uploadid, commentid, fileName) values (2, 9, 'http://dummyimage.com/111x100.png/ff4444/ffffff');
insert into upload (uploadid, commentid, fileName) values (3, 6, 'http://dummyimage.com/134x100.png/cc0000/ffffff');

-- event_category --

insert into event_category (eventid, categoryid) values (1, 7);
insert into event_category (eventid, categoryid) values (2, 13);
insert into event_category (eventid, categoryid) values (3, 10);
insert into event_category (eventid, categoryid) values (4, 13);
insert into event_category (eventid, categoryid) values (5, 12);
insert into event_category (eventid, categoryid) values (6, 2);
insert into event_category (eventid, categoryid) values (7, 2);
insert into event_category (eventid, categoryid) values (8, 10);
insert into event_category (eventid, categoryid) values (9, 5);
insert into event_category (eventid, categoryid) values (10, 3);
insert into event_category (eventid, categoryid) values (11, 14);
insert into event_category (eventid, categoryid) values (12, 11);
insert into event_category (eventid, categoryid) values (13, 9);
insert into event_category (eventid, categoryid) values (14, 13);
insert into event_category (eventid, categoryid) values (15, 3);
insert into event_category (eventid, categoryid) values (16, 15);
insert into event_category (eventid, categoryid) values (17, 11);
insert into event_category (eventid, categoryid) values (18, 9);
insert into event_category (eventid, categoryid) values (19, 6);
insert into event_category (eventid, categoryid) values (20, 10);
insert into event_category (eventid, categoryid) values (21, 11);
insert into event_category (eventid, categoryid) values (22, 13);
insert into event_category (eventid, categoryid) values (23, 3);
insert into event_category (eventid, categoryid) values (24, 13);
insert into event_category (eventid, categoryid) values (25, 9);
insert into event_category (eventid, categoryid) values (26, 10);
insert into event_category (eventid, categoryid) values (27, 9);
insert into event_category (eventid, categoryid) values (28, 11);
insert into event_category (eventid, categoryid) values (29, 6);
insert into event_category (eventid, categoryid) values (30, 10);

-- event_tag --
insert into event_tag (eventid, tagid) values (1, 11);
insert into event_tag (eventid, tagid) values (2, 4);
insert into event_tag (eventid, tagid) values (3, 1);
insert into event_tag (eventid, tagid) values (4, 6);
insert into event_tag (eventid, tagid) values (5, 15);
insert into event_tag (eventid, tagid) values (6, 9);
insert into event_tag (eventid, tagid) values (7, 1);
insert into event_tag (eventid, tagid) values (8, 13);
insert into event_tag (eventid, tagid) values (9, 5);
insert into event_tag (eventid, tagid) values (10, 10);
insert into event_tag (eventid, tagid) values (11, 7);
insert into event_tag (eventid, tagid) values (12, 15);
insert into event_tag (eventid, tagid) values (13, 9);
insert into event_tag (eventid, tagid) values (14, 14);
insert into event_tag (eventid, tagid) values (15, 12);
insert into event_tag (eventid, tagid) values (16, 6);
insert into event_tag (eventid, tagid) values (17, 11);
insert into event_tag (eventid, tagid) values (18, 13);
insert into event_tag (eventid, tagid) values (19, 3);
insert into event_tag (eventid, tagid) values (20, 2);
insert into event_tag (eventid, tagid) values (21, 15);
insert into event_tag (eventid, tagid) values (22, 1);
insert into event_tag (eventid, tagid) values (23, 14);
insert into event_tag (eventid, tagid) values (24, 10);
insert into event_tag (eventid, tagid) values (25, 10);
insert into event_tag (eventid, tagid) values (26, 3);
insert into event_tag (eventid, tagid) values (27, 12);
insert into event_tag (eventid, tagid) values (28, 4);
insert into event_tag (eventid, tagid) values (29, 7);
insert into event_tag (eventid, tagid) values (30, 9);