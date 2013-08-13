DROP TABLE IF EXISTS "cronnables";
CREATE TABLE "cronnables" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "group_id" INTEGER NOT NULL , "url" VARCHAR NOT NULL , "interval" NUMERIC NOT NULL , "attempts" numeric, "last_attempt" DATETIME, "added" datetime DEFAULT CURRENT_TIMESTAMP);
INSERT INTO "cronnables" VALUES(1,1,'http://localhost',5,0,NULL,'2013-08-13 07:50:14');
INSERT INTO "cronnables" VALUES(2,1,'http://localhost',5,0,NULL,'2013-08-13 07:50:33');
DROP TABLE IF EXISTS "groups";
CREATE TABLE "groups" ("id" integer PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "name" VARCHAR NOT NULL  UNIQUE , "added" datetime DEFAULT CURRENT_TIMESTAMP);
INSERT INTO "groups" VALUES(1,'loop','2013-08-13 07:48:29');
CREATE INDEX "cronnable_indices" ON "cronnables" ("id" ASC, "group_id" ASC);
