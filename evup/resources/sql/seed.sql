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

CREATE TABLE upload(
  uploadid SERIAL PRIMARY KEY,
  filename TEXT NOT NULL
);

CREATE TABLE users(
  userid SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL CONSTRAINT unique_usernam_uk UNIQUE,
  name VARCHAR(150) NOT NULL, 
  email TEXT NOT NULL CONSTRAINT user_email_uk UNIQUE,
  password TEXT NOT NULL,
  userphoto INTEGER REFERENCES upload (uploadid) on DELETE SET NULL ON UPDATE CASCADE,
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
    eventphoto INTEGER REFERENCES upload (uploadid) on DELETE SET NULL ON UPDATE CASCADE,
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

------------------------------------------------------------------------------------------------------

-- upload --

insert into upload (filename) values ('userDefault.png');
insert into upload (filename) values ('eventDefault.jpeg');

---1234
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mfalcus0', 'Micky Falcus', 'mfalcus0@google.com.hk', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('esergent1', 'Elfrida Sergent', 'esergent1@trellian.com', '$2a$12$GNYQT3cnVKmhgi5FMyjBuekVSDuYQ9J3brx.1YDQ9vyDOhzX5/4U6', 1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('glanahan2', 'Gaultiero Lanahan', 'glanahan2@rediff.com', '$2a$12$aIJGp62nFW6Qz2Bmyo.2ouzpalZjMqLZs2s06H2tYqcCLgpSQt0zG', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('dblackader3', 'Darlene Blackader', 'dblackader3@shareasale.com', '$2a$12$rkOFfYybMiOktfTnAX6VAewV7hKHGF.HvVKk6sWofjWUE6ufylRYS', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('hhead4', 'Harald Head', 'hhead4@apple.com', '$2a$12$nbHqkY.0JP6.N1d4BTj7mu5W9tRdfzI/V81q61o.RMRhY32c/vy9G', 1, 'Disabled', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('ckirtland5', 'Cathrin Kirtland', 'ckirtland5@fotki.com', '$2a$12$FNoX/oiWL6YfIpa/AyYUZu/RyE65BxRRYCVgNmwmOwZCjZ3xU2nT.', 1, 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mdougary6', 'Merilee Dougary', 'mdougary6@artisteer.com', '$2a$12$x..4MPUpLKYfrL.b6md3AO/gMGPRVtxbaIoreHQEH1K34MyCb1R8e', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('bbullman7', 'Brandyn Bullman', 'bbullman7@amazon.co.jp', '$2a$12$uQ6hxS1VICoKLTAxN4VIvOCN9GOJYWrL.50xiecGDx4HsOJLuMLHK', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('dwhatmough8', 'Dierdre Whatmough', 'dwhatmough8@va.gov', '$2a$12$WTeWqqwBQY52XF3IrwsUCOMzXit0Oa705TOME9TAeJ.wuudg8Z28G', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('jsteptoe9', 'Jojo Steptoe', 'jsteptoe9@theglobeandmail.com', '$2a$12$MPeic3/aCWO8Fqujw86EbOwI4EZW4nWoHeH/34BFdVHzC4KXnRw4u',1, 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('pbentkea', 'Patty Bentke', 'pbentkea@hp.com', '$2a$12$H3ksZb9D2lgfH5jS5EFJd.mM7JM.j1CFGCujDM6ojPyviM82Zw1bG', 1, 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('rbuckleeb', 'Rafael Bucklee', 'rbuckleeb@china.com.cn', '$2a$12$7CItKvaiiEGrW9GdxMDOSe/m0h76BeEY36Ths41IVGQhuDBn29CiO', 1, 'Blocked', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('bbeckerc', 'Barbabas Becker', 'bbeckerc@mysql.com', '$2a$12$6grWpzjqrUWQUu1R.qP5.OKY2Y4KYCMyR7BEe4wAnTNVLB36m6SVS', 1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('pbromwichd', 'Pooh Bromwich', 'pbromwichd@delicious.com', '$2a$12$vGby4k2Q9bSVctmk6qJBQ.T.KnYLBMTvjTu94c1dvKxW/D8VDBKZq', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('cgirardettie', 'Cyrillus Girardetti', 'cgirardettie@dropbox.com', '$2a$12$Bae4xKFJke0VCq/toEP2n.gdv30onGj9tf7bDTCcWUK5JZrb40eFq', 1, 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('rdacheyf', 'Rollin Dachey', 'rdacheyf@gizmodo.com', '$2a$12$viNjoc/pkk2tb8Rw98sxD.KsvS3KeHERGQscVESkhDOmPgHHAfNPK', 1, 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('nmayg', 'Noe May', 'nmayg@tmall.com', '$2a$12$SAaRbpQ/X8E28i/HKGVx3eG4o8cSwRwZ1zYVZdYAVfLRYz404fmkO', 1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('oarnelyh', 'Ofelia Arnely', 'oarnelyh@furl.net', '$2a$12$ykFgvUQSzcn4qTbqs72GaO0dGZ8sm.hdfAY54FQycqvH.wnuHsh.e', 1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('hkohneni', 'Hilliard Kohnen', 'hkohneni@flickr.com', '$2a$12$H79gHsKwoLIWXSZASHEOq.EM3N0lMmL2886H5UdvQj4pRTiuYc5Ie', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('lsommervillej', 'Lindsey Sommerville', 'lsommervillej@ihg.com', '$2a$12$R1zkONLvhzMVC6sOdHcGHOQv5zxv2vGNc6j5FN/MAb0cMV68h2LCq', 1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('bbuzzingk', 'Berke Buzzing', 'bbuzzingk@vinaora.com', '$2a$12$URLkNk9xF.ZLswkDJI3SHOkIP.ldeVTkkeDEAWhYIssAi2sVwBMzi', 1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('wkensettl', 'Wren Kensett', 'wkensettl@freewebs.com', '$2a$12$xCMI5mdm2MlEdGOyiNpEf.Sw9FJuMkyW6zjspo6PY/3wgnjA1fn0K', 1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mpietrowskim', 'Murial Pietrowski', 'mpietrowskim@hibu.com', '$2a$12$JrfRlgEHwCaThx0Rrg70eea5M6WsIpZEdhLTPgmiAEQqaP.IThVPy', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('kpictonn', 'Kristian Picton', 'kpictonn@hatena.ne.jp', '$2a$12$90O6rm20N6fxDoS0sCfP3OnsDrdnQn4UHED.fF.HEmL19ryvWaAaS', 1, 'Active', 'Admin');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mbarbrooko', 'Maddie Barbrook', 'mbarbrooko@addthis.com', '$2a$12$jjE5U.ZnAqcDLt/T09J/G.JxQZyehlk8OTMZOqblhbZu1GBxz9E9C', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('kdarthep', 'Kathlin Darthe', 'kdarthep@nymag.com', '$2a$12$2J1gpdNZIdp6EFjSLdrcpOx7dqNYRPKJqvFtAdMyIJFl.4ILz1tpW', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('mpillingtonr', 'Moses Pillington', 'mpillingtonr@ucoz.ru', '$2a$12$3mdQpI.u5ZKuQxbiG.kb4.B4fSVp08ZFvbE1imHJWjKzfYu7FgTHK',1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('dclemensens', 'Davine Clemensen', 'dclemensens@sina.com.cn', '$2a$12$q5FruFlJfr1T0top08UHg.Vr9hZSJ2ZjGy1SHCC8yeEfxVPHfm2SG', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto, accountstatus, usertype) values ('radamskit', 'Roddie Adamski', 'radamskit@opensource.org', '$2a$12$TVx9ET0bVi/nFgkDSM5cGeg8s3GyE8yVtjeJ3p2CFPaHF6dlqgVna', 1, 'Active', 'Organizer');

insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('admin', 'Administrator', 'admin@evup.com', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 1, 'Active', 'Admin');
insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('organizer', 'Organizer', 'organizer@evup.com', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 1, 'Active', 'Organizer');
insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('user', 'User', 'user@evup.com', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 1, 'Active', 'User');
insert into users (username, name, email, password, userphoto,accountstatus, usertype) values ('password', 'Password Test', 'cisat14362@nubotel.com', '$2a$12$MKHXzV7jJJNlWeOYhwOSLe.ukGW.UGu..wXVth0SwWI8Ewn5EZnwe', 1, 'Active', 'User');



-- event --
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 2, 'Kovacek-Conn', false, '16 Brentwood Park', 'Nondisplaced fracture of base of neck of left femur', 2, '2023-11-08', '2023-11-30');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 13, 'Wilkinson-Klein', false, '21812 Ohio Alley', 'Benign neoplasm of scrotum',2, '2023-02-06', '2023-02-16');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 17, 'McDermott, Hammes and Medhurst', true, '52 Onsgard Trail', 'Other injury of superior mesenteric artery', 2, '2023-01-03', '2023-01-31');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 18, 'Marquardt and Sons', false, '0752 Mayfield Park', 'Nondisplaced fracture of distal phalanx of right ring finger, sequela',2, '2023-01-24', '2023-01-25');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 20, 'Rutherford, DuBuque and MacGyver', false, '3926 Montana Avenue', 'Person boarding or alighting a three-wheeled motor vehicle injured in collision with two- or three-wheeled motor vehicle', 2, '2023-01-03', '2023-02-15');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 21, 'Mohr, Cummerata and Rempel', false, '902 Springview Center', 'Unspecified intracranial injury with loss of consciousness of 1 hour to 5 hours 59 minutes, sequela', 2, '2023-12-02', '2023-12-03');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 22, 'Trantow-Zieme', false, '96 Sauthoff Center', 'Corrosion of unspecified degree of multiple right fingers (nail), not including thumb, initial encounter', 2, '2022-11-29', '2022-12-29');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 28, 'Beahan, Brakus and Schultz', true, '5 1st Circle', 'Displaced fracture of fifth metatarsal bone, left foot, subsequent encounter for fracture with nonunion', 2, '2023-01-03', '2023-02-17');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 30, 'Stiedemann, Heidenreich and Bradtke', false, '73 Miller Terrace', 'Other specified and unspecified injuries of neck', 2, '2023-01-19', '2023-01-24');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 2, 'Lesch Inc', false, '94086 Vahlen Avenue', 'Breakdown (mechanical) of surgically created arteriovenous fistula, subsequent encounter', 2, '2023-01-02', '2023-01-03');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 17, 'Haag, Reinger and Hegmann', true, '61 Forest Park', 'Periprosthetic osteolysis of internal prosthetic right knee joint, sequela', 2, '2022-11-29', '2023-01-01');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 18, 'Reynolds, Mertz and Adams', false, '18331 Bonner Alley', 'Other superficial bite of right upper arm, initial encounter', 2, '2022-12-08', '2022-12-09');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 20, 'Bauch, Mitchell and Mraz', true, '583 Heffernan Junction', 'Ataxia following unspecified cerebrovascular disease', 2, '2023-02-26', '2023-02-27');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 21, 'Leannon and Sons', true, '4626 Claremont Junction', 'Type AB blood, Rh positive', 2, '2023-01-27', '2023-01-30');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 22, 'Lebsack, Hickle and Kassulke', false, '689 Debs Point', 'Other cyst of bone, left hand', 2, '2022-12-23', '2022-12-25');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 28, 'Raynor, Durgan and Pfeffer', false, '5137 Bonner Junction', 'Diabetes mellitus due to underlying condition with proliferative diabetic retinopathy with combined traction retinal detachment and rhegmatogenous retinal detachment, unspecified eye', 2, '2022-12-01', '2022-12-15');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 30, 'Lehner-Yundt', false, '765 Prairie Rose Hill', 'Maternal care for other malpresentation of fetus', 2, '2022-12-29', '2023-01-27');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 2, 'Bauch-Pagac', true, '6762 Mallard Point', 'Laceration of unspecified urinary and pelvic organ, sequela', 2, '2023-01-04', '2023-01-30');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 13, 'Hermann-Stamm', true, '479 Duke Drive', 'Unspecified fracture of shaft of unspecified tibia, initial encounter for open fracture type IIIA, IIIB, or IIIC', 2, '2023-03-09', '2023-03-12');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 17, 'Lowe, Renner and Hand', true, '726 Mallard Junction', 'Nondisplaced fracture of lower epiphysis (separation) of unspecified femur, initial encounter for open fracture type I or II', 2, '2022-12-16', '2023-02-02');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 18, 'Kreiger-Leuschke', true, '7163 Maryland Park', 'Laceration of other blood vessels of thorax, left side, initial encounter', 2, '2023-02-08', '2023-02-17');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 20, 'Schneider and Sons', true, '498 Miller Trail', 'Pedestrian injured in unspecified nontraffic accident', 2, '2022-11-21', '2022-11-29');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 21, 'pollich LLC', false, '280 Banding Parkway', 'Activities involving personal hygiene and interior property and clothing maintenance', 2, '2023-10-25', '2023-10-30');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 22, 'Brown LLC', true, '3548 Crest Line Plaza', 'Adverse effect of predominantly alpha-adrenoreceptor agonists', 2, '2022-12-02', '2022-12-16');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 28, 'Hermiston and Sons', true, '06 Summerview Lane', 'Sepsis due to Methicillin resistant Staphylococcus aureus', 2, '2023-01-17', '2023-01-18');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 30, 'Ratke-Conn', true, '5 Dovetail Park', 'Person boarding or alighting from bus injured in collision with fixed or stationary object', 2, '2023-11-17', '2023-12-06');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 2, 'Green, Walter and Boyle', true, '81 Upham Road', 'Displaced supracondylar fracture with intracondylar extension of lower end of unspecified femur, subsequent encounter for open fracture type IIIA, IIIB, or IIIC with malunion', 2, '2022-11-11', '2022-11-21');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 13, 'Sauer, Gerlach and Kiehn', true, '002 Lindbergh Center', 'Asphyxiation due to plastic bag, accidental, sequela', 2, '2023-12-01', '2023-12-15');
insert into event ( userid, eventname, public, eventaddress, description, eventcanceled, eventphoto, startdate, enddate) values ( 17, 'Graham-Lemke', true, '7585 Oriole Terrace', 'Flaccid hemiplegia', false, 2, '2023-01-06', '2023-01-08');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 32, 'Christmas Party', true, '001 North Pole', 'Christmas Party in Santa house. Everyone can join!', 2, '2022-12-24', '2022-12-25');
insert into event ( userid, eventname, public, eventaddress, description, eventphoto, startdate, enddate) values ( 13, 'Jerde Inc', false, '519 Arapahoe Parkway', 'Unspecified parasitic disease', 2, '2022-12-19', '2023-02-13');
-- attendee --

insert into attendee (attendeeid, eventid) values (1, 1);
insert into attendee (attendeeid, eventid) values (1, 10);
insert into attendee (attendeeid, eventid) values (1, 11);
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

-- report --

insert into report (reportid, reporterid, eventid, message, reportstatus) values (1, 1, 1, 'This event is not suitable for up students.', false);
insert into report (reportid, reporterid, eventid, message, reportstatus) values (2, 3, 22, 'This event is abusive.', true);
insert into report (reportid, reporterid, eventid, message, reportstatus) values (3, 2, 3, 'The organizer of this event was rude to me.', false);
insert into report (reportid, reporterid, eventid, message, reportstatus) values (4, 3, 1, 'This is spam!', false);
insert into report (reportid, reporterid, eventid, message, reportstatus) values (5, 1, 21, 'Wrong category', true);
insert into report (reportid, reporterid, eventid, message, reportstatus) values (6, 1, 23, 'The event image is inappropriate...', true);
insert into report (reportid, reporterid, eventid, message, reportstatus) values (7, 5, 28, 'Fraud', false);
insert into report (reportid, reporterid, eventid, message, reportstatus) values (8, 3, 1, 'Should be tagged as adult content', true);
insert into report (reportid, reporterid, eventid, message, reportstatus) values (9, 6, 30, 'Should be tagged as adult content', true);

--invitation --

insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (4, 8, 1, null);
insert into invitation (inviterid, inviteeid, eventid, invitationstatus) values (4, 7, 2, null);
insert into invitation (inviterid, inviteeid, eventid, invitationstatus) values (7, 4, 3, null);
insert into invitation (inviterid, inviteeid, eventid, invitationstatus) values (9, 3, 4, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (1, 2, 5, true);
insert into invitation (inviterid, inviteeid, eventid, invitationstatus) values (1, 4, 6, true);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (2, 22, 7, false);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (3, 22, 8, true);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (4, 3, 8, false);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (3, 8, 9, false);

insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (2, 1, 2, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (3, 1, 3, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (2, 1, 3, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (3, 1, 2, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (2, 1, 5, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (3, 1, 8, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (2, 1, 2, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (3, 1, 4, null);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (4, 1, 4, false);
insert into invitation ( inviterid, inviteeid, eventid, invitationstatus) values (3, 1, 1, false);

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

insert into Comment (authorId, eventId, commentContent, commentDate) values (4, 4, 'I am looking forward to it!!', '2022-12-20');
insert into Comment (authorId, eventId, commentContent, commentDate) values (8, 8, 'I have some questions about the event. Someone can help me?', '2022-10-10');
insert into Comment (authorId, eventId, commentContent, commentDate) values (7, 7, 'Looks very useful', '2022-11-10');
insert into Comment (authorId, eventId, commentContent, commentDate) values (2, 1, ':)', '2022-11-30');
insert into Comment (authorId, eventId, commentContent, commentDate) values (5, 5, ':(', '2022-12-22');
insert into Comment (authorId, eventId, commentContent, commentDate) values (1, 1, 'It was fun', '2022-12-02');
insert into Comment (authorId, eventId, commentContent, commentDate) values (9, 9, 'This event changed my life!', '2022-12-20');
insert into Comment (authorId, eventId, commentContent, commentDate) values (2, 2, 'I did not like the event. I am disappointed.', '2022-12-20');
insert into Comment (authorId, eventId, commentContent, commentDate) values (7, 7, 'Where is the event?', '2022-11-06');
insert into Comment (authorId, eventId, commentContent, commentDate) values (2, 1, 'Nice!', '2022-11-19');

insert into Comment (authorId, eventId, parentId, commentContent, commentDate) values (2, 1, 6, 'Much appreciated! Glad you liked it ☺️', '2022-12-03');
insert into Comment (authorId, eventId, parentId, commentContent, commentDate) values (1, 1, 10, ':) :)', '2022-11-20');
insert into Comment (authorId, eventId, parentId, commentContent, commentDate) values (2, 1, 10, ':)', '2022-11-20');

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
insert into organizerrequest ( requesterid, requeststatus) values (1, true);
insert into organizerrequest ( requesterid) values (8);

-- notification --

insert into notification ( receiverid, eventid, notificationdate, notificationtype, notificationstatus) values (1, 5, CURRENT_TIMESTAMP, 'EventChange', false);
insert into notification ( receiverid, joinrequestid, notificationdate, notificationtype, notificationstatus) values ( 1, 1, CURRENT_TIMESTAMP, 'JoinRequestReviewed', false);
insert into notification ( receiverid, organizerrequestid, notificationdate, notificationtype, notificationstatus) values (1, 4, CURRENT_TIMESTAMP, 'OrganizerRequestReviewed', false);
insert into notification ( receiverid, invitationid, notificationdate, notificationtype, notificationstatus) values ( 1, 11, CURRENT_TIMESTAMP, 'InviteReceived', false);
insert into notification ( receiverid, invitationid, notificationdate, notificationtype, notificationstatus) values (1, 6, CURRENT_TIMESTAMP, 'InviteAccepted', false);
insert into notification ( receiverid, pollid, notificationdate, notificationtype, notificationstatus) values ( 1, 7, CURRENT_TIMESTAMP, 'NewPoll', false);
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