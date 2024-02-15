# PlayQuest
1 Purpose 
 
 
Students these days are looking for fun ways of learning concepts. Adding gamified elements to study has proven scientific effects like better concentration and increased learning and understanding scope. This is why software company YantrikiSoft is willing to prepare an app that can enable students / users to learn concepts they like by a gamified approach. 
The mobile app will host multiple categories of championships based on a variety of subjects. This project idea will make learning and self-assessment easy and fun. Users have the capability to choose their areas of interest and engage in various championships aligned with those interests. The achievements in these championships will be documented and stored, enabling the calculation of users' proficiency in relevant domains and assessing their employability.
The administrative web portal will be there for the company / product owners and authorities to manage the users, technical aspects and the content to be displayed on the app. 
 
3.2 Decision of Scope 
 

This project aims to develop a gamified app and admin control portal will include several functions like playing championships, maintaining profile, collecting coins and rewards, etc. Users can register themselves and play. In admin portal, admins and employees of YantrikiSoft can add championships, categories of championships, maintain question bank, etc. 
 
3.3 Methodology for solving this proposed theme 
 
 
3.3.1 System Architecture 
 
 
In our solution, we are following this architectures for PlayQuest mobile app. The architecture services state the path from login to playing the first championship and collecting rewards. 
 
The hierarchical architecture structure shows the way questions will be aligned in particular categories and sub-categories. The diagrams highlight key navigation aspect of the mobile app. 
 

1.	Sign-Up: Users have the option to register using their mobile number, and the registration process involves OTP generation for authentication.
2.	Log-In: Upon registration, users are automatically logged in and remain so until they choose to log out. Subsequent logins require OTP verification.
3.	Championship Category Selection: Users can explore championship categories on the dedicated page and choose their preferred championship.
4.	Participate in Championships: Users can actively engage in championships by answering questions and earning rewards. For users not logged in, participation requires logging in and subsequent reward redemption.
5.	Game Modes: Users can enjoy various game modes, with two currently specified by the company. Each mode presents distinct questions and adheres to specific championship rules. Additional game modes may be introduced in future updates.
6.	Profile Management: Once logged in, users can maintain and customize their profiles, monitor their progress, and access additional features introduced in future updates.


 
 
In this system architecture, categories represent various championships categorized by subject, topic, or learning course type, such as Java championships or Algebra championships. Users have the flexibility to choose a category and then select a specific championship within that category. Championships serve as the games users engage with on the app, each featuring a description, reward structure, and other details aligned with the chosen game mode.
1.	Categories: These encompass a diverse range of subjects, topics, and learning courses available for user selection.
2.	Sub-Categories / Championships: Upon choosing a category, users can further select a championship within that category. Championships serve as the interactive games on the app, each providing details like descriptions and reward structures based on the selected game mode.
3.	Game Modes: The project focuses on planning for two game modes: Quick Hit and Select Gift & Play.
3.1. Quick Hit: This mode involves users quickly answering two questions to win rewards. It is the shortest and easiest mode to earn coins, but a single incorrect answer cancels the reward.
3.2. Select Gift and Play: In this mode, users can choose a gift or coupon code before playing the game. The selected gifts are awarded upon winning the game.
4.	Question Bank: The Question Bank is a repository of diverse questions covering a wide range of subjects. These questions are labeled with their respective subjects, allowing administrators to utilize them in games. Questions are randomly presented during gameplay.
 
4.	System Requirements and Specifications 
 
 
4.1 Introduction 
 
 
Intended Audience and Reading Suggestions Developers, Project managers and Marketing staff are the intended readers of the document, the content for each reader in the document is: 
1	Developers: The document specifies main system requirements and other technical requirements to refer while developing the software. 
2	Project managers: Main Document requirements are specified and described in this document to refer and manage. 
3	Marketing department: The document contains the specific features that the system will have after it is developed. Readers are suggested to read the document sequentially as mentioned in the table of contents. 
 
4.2 User Classes and Characteristics 
 
 
There are two main users: 
1	Student / Users - Students will sign up into the App and will have a profile to maintain. With the valid credentials it will log into the system. Students will compete in championships of their interests, in a particular domain. Students / Users can win rewards and analyze their knowledge. 
2	Company / Admins (YantrikiSoft) - Company will sign up into the admin portal and will have a username / email and password. With the valid credentials it will log into the system. Companies will edit/add/delete data as needed in the app and also maintain the analytics of app downloads and other details. After the login admins can see the analytics and settings of the app. 
 
4.3 Operating Environment 
 
 
Software Requirement: 
•	Operating System - Android, Linux and Windows.
•	Database - MySQL
•	Language - Flutter(Android Studio), JavaScript, PHP. 
•	IDE/Framework - Android Studio, VS Code  
 
4.4 Functional Requirements 
 
 
•	REQ-1: Students / users should be able to browse different championships, jobs and courses as per their interests. 
•	REQ-2: Admins should be able to view different users in app as per their requirements. 
•	REQ-3: Students / users should be able to upload their resume and other required profile data. 
•	REQ-4: Students / users should be able to create, insert, update and delete their respective data in the App. 
•	REQ-5: Admins should be able to create, insert, update and delete their respective data in the Admin portal. 
 
•	REQ-6: Both the students / users and admins should be able to contact each other in case of reporting content. 
 
4.5 Non-functional Requirements 
 
 
4.5.1 Performance Requirements 
Both PlayQuest App and Admin portal must perform smoothly. They should use less memory space. 
UI should not be much complicated and should be easy to use. 
 
4.5.2 Safety Requirements 
The system should not operate if security attacks have become obvious. 
The system should not operate in a very high temperature mode. 
 
4.5.3 Security Requirements 
Unauthorized access to both the systems should not be allowed. 
Some kind of verification must be provided to get access to both the systems. 
 
 
4.5.4 Software Quality Attributes 
Quality of both the systems must be maintained and UI should be friendly to the users. It must not be lagging and it should provide quick search results and work smoothly. It must be secured. 
 
4.5.5 Business Rules 
•	Students / users should have valid documents, educational certificates and skills, if required. 
•	Admins should be authorized and have valid employee/authorization ID 

 






5.3 Class Diagram 
 
 
					Fig5: Class Diagram
