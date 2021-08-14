BEGIN TRANSACTION;

----
-- Table structure for Orders
----
CREATE TABLE "Orders" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"datetime"	INTEGER NOT NULL,
	"userId"	INTEGER NOT NULL,
	"notes"	INTEGER,
	"payed"	INTEGER NOT NULL DEFAULT 0,
	"createdAt"	INTEGER NOT NULL
);

----
-- Table structure for OrderDishes
----
CREATE TABLE "OrderDishes" (
	"orderId"	INTEGER NOT NULL,
	"dishId"	TEXT NOT NULL,
	"dishQty"	TEXT NOT NULL,
	"dishNotes"	TEXT
);

----
-- Table structure for Dishes
----
CREATE TABLE "Dishes" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"name"	TEXT NOT NULL,
	"price"	NUMERIC NOT NULL,
	"deleted"	INTEGER NOT NULL DEFAULT 0
);

----
-- Table structure for Users
----
CREATE TABLE "Users" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"name"	TEXT NOT NULL,
	"email"	TEXT NOT NULL,
	"passwordHash"	TEXT NOT NULL,
	"classe"	TEXT NOT NULL,
	"verified"	INTEGER NOT NULL DEFAULT 0,
	"isStaff"	INTEGER NOT NULL,
	"createdAt"	INTEGER NOT NULL,
	"token"	TEXT,
	"lastLogin"	INTEGER,
	"emailToken"	TEXT,
	"lastView"	TEXT
);

COMMIT;
