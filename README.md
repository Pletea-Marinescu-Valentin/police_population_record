# Police Population Record

The **Police Population Record** project is a comprehensive application designed to manage information about citizens, officers, driving licenses, crimes, and identity documents. This document outlines the design, implementation, and functionalities of the system.

---

## Features

- **Citizen Management**: Add, edit, delete, and search citizens with relevant details like name, address, and more.
- **Officer Management**: Manage officers, view their details, and associate them with cases.
- **Crime Management**: Add, edit, delete, and search crimes, linking them with citizens and officers.
- **Driving Licenses and Identity Documents**: View, add, and manage licenses and documents for citizens.
- **Reports and Statistics**:
  - Generate detailed reports about citizens, crimes, and licenses.
  - View graphical statistics for better insights.
- **Export Functionality**: Export reports to **PDF** or **CSV** formats.

---

## System Requirements

- A relational database (MySQL recommended).
- PHP (7.x or higher).
- Web server (e.g., Apache, Nginx).
- [FPDF](http://www.fpdf.org/) library for PDF generation.

---

## Database Design

### Tables and Relationships
The database consists of the following tables:

1. **Addresses**:
   - Stores information about citizen addresses.

2. **Citizens**:
   - Contains citizen details like name, birth date, and address.
   - Linked to crimes, driving licenses, and identity documents.

3. **Crimes**:
   - Records details of crimes with associations to citizens and officers.

4. **Driving_Licenses**:
   - Manages driving license details such as category, issue date, and expiry date.

5. **Identity_Documents**:
   - Contains details about citizens' identity documents.

6. **Officers**:
   - Stores officer details and their ranks.

7. **Users**:
   - Handles application user accounts with roles such as Admin, Officer, or User.

8. **Citizens_Crimes**:
   - Manages the many-to-many relationship between citizens and crimes.

---

## Folder Structure

- **assets/**: Static resources like CSS and JavaScript files.
  - index.css: Global styles.
  - style.css: Component-specific styles.
  - script.js: JavaScript for interactivity.

- **config/**: Configuration files.
  - database.php: Database connection management using PDO.

- **fpdf/**: External library for PDF generation.
  - fpdf.php: Main FPDF library.
  - font/: Fonts used for PDF reports.

- **includes/**: Reusable components for the application.
  - header.php: Displays the page header.
  - footer.php: Displays the page footer.
  - functions.php: Contains reusable functions for validation and access control.

- **pages/**: Contains all the application functionalities.

---

## Key Reports and Queries

### Reports
1. **Citizens with Longest License Duration**:
   - Find citizens who have held their licenses for the longest time.
   - Example query:
     ```sql
     SELECT CONCAT(c.First_Name, ' ', c.Last_Name) AS Citizen_Name, 
            TIMESTAMPDIFF(YEAR, dl.Issue_Date, CURDATE()) AS License_Years
     FROM Citizens c
     JOIN Driving_Licenses dl ON c.Citizen_ID = dl.Citizen_ID
     WHERE TIMESTAMPDIFF(YEAR, dl.Issue_Date, CURDATE()) = (
         SELECT MAX(TIMESTAMPDIFF(YEAR, Issue_Date, CURDATE()))
         FROM Driving_Licenses
     )
  
2. **Officers with Most Types of Crimes**:
   - Find officers who have investigated the most distinct types of crimes.
   - Example query:
     ```sql
     SELECT o.First_Name, o.Last_Name, COUNT(DISTINCT cr.Description) AS Total_Types
     FROM Officers o
     JOIN Crimes cr ON o.Officer_ID = cr.Officer_ID
     GROUP BY o.Officer_ID
     HAVING COUNT(DISTINCT cr.Description) = (
         SELECT MAX(Total_Types)
         FROM (
             SELECT cr.Officer_ID, COUNT(DISTINCT cr.Description) AS Total_Types
             FROM Crimes cr
             GROUP BY cr.Officer_ID
         ) AS Subquery
     )

### Statistics
- Citizens per city.
- Distribution of driving license categories.
- Number of cases per officer.
- Crimes by month.

## Installation

1. **Clone the Repository**:
   git clone https://github.com/Pletea-Marinescu-Valentin/police_population_record.git
