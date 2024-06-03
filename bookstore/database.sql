-- Create the BookStore database
CREATE DATABASE IF NOT EXISTS BookStore;
USE BookStore;

-- Create the Book table
CREATE TABLE Book(
    BookID varchar(50),
    BookTitle varchar(200),
    ISBN varchar(20),
    Price double(12,2),
    Author varchar(128),
    Type varchar(128),
    Image varchar(128),
    PRIMARY KEY (BookID)
);

-- Create the Users table
CREATE TABLE Users(
    UserID int not null AUTO_INCREMENT,
    UserName varchar(128),
    Password varchar(255),  -- Changed to 255 to store hashed passwords
    Role ENUM('admin', 'user') NOT NULL,  -- Added role column
    PRIMARY KEY (UserID)
);

-- Create the Customer table
CREATE TABLE Customer (
    CustomerID int not null AUTO_INCREMENT,
    CustomerName varchar(128),
    CustomerPhone varchar(12),
    CustomerIC varchar(14),
    CustomerEmail varchar(200),
    CustomerAddress varchar(200),
    CustomerGender varchar(10),
    UserID int,
    PRIMARY KEY (CustomerID),
    CONSTRAINT FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Create the Order table
CREATE TABLE `Order`(
    OrderID int not null AUTO_INCREMENT,
    CustomerID int,
    BookID varchar(50),
    DatePurchase datetime,
    Quantity int,
    TotalPrice double(12,2),
    Status varchar(1),
    PRIMARY KEY (OrderID),
    CONSTRAINT FOREIGN KEY (BookID) REFERENCES Book(BookID) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Create the Cart table
CREATE TABLE Cart(
    CartID int not null AUTO_INCREMENT,
    CustomerID int,
    BookID varchar(50),
    Price double(12,2),
    Quantity int,
    TotalPrice double(12,2),
    PRIMARY KEY (CartID),
    CONSTRAINT FOREIGN KEY (BookID) REFERENCES Book(BookID) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE SET NULL ON UPDATE CASCADE
);


-- Insert initial data into the Book table
INSERT INTO `Book`(`BookID`, `BookTitle`, `ISBN`, `Price`, `Author`, `Type`, `Image`) VALUES 
('B-001', 'Lonely Planet Australia (Travel Guide)', '123-456-789-1', 136, 'Lonely Planet', 'Travel', 'image/travel.jpg'),
('B-002', 'Crew Resource Management, Second Edition', '123-456-789-2', 599, 'Barbara Kanki', 'Technical', 'image/technical.jpg'),
('B-003', 'CCNA Routing and Switching 200-125 Official Cert Guide Library', '123-456-789-3', 329, 'Cisco Press', 'Technology', 'image/technology.jpg'),
('B-004', 'Easy Vegetarian Slow Cooker Cookbook', '123-456-789-4', 75.9, 'Rockridge Press', 'Food', 'image/food.jpg');