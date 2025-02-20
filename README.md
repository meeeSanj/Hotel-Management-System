This project aims to develop a web-based online hotel booking system that streamlines the booking process for both customers and hotel staff. The system provides an intuitive and user-friendly interface that allows customers to easily search for available rooms, order food, and other services like laundry services.
Traditional hotel booking systems often involve manual processes that can be time-consuming and prone to errors. This project addresses these issues by automating the booking process and providing a centralized platform for managing reservations.

The system utilizes HTML and basic CSS for the front-end and MySQL as the back-end database. PHP serves as the server-side scripting language to connect the front-end and back-end components.

Project Scope: The system will include the following core functionalities:
•	User Registration and Login: Users can create accounts and log in to access their booking history and preferences.
•	Room Booking: Customers can book an available room based on their preferences for their required number of days.
•	Ordering Food: Customer can order the food and the quantity of their choice from the given list of food items given.
•	Bill: It allows the user to see the items they have selected or/and deteled so far along with the total amount.


Database Design
 The database schema includes the following:

Tables:
•	roombooking: Stores room details (sl_no, room, availability, price per day)
•	users: Stores user information (id, username, password, email)
•	order: Stores order information (order_id, item, quantity, cost, total_price)
•	laundry_services: Stores laundry details (sl_no, item, price)
•	order_deletion_log:  Stores details of deleted order (log_id, order_id, item, quantity, cost, total_price, deteted_at)
•	Food: 
	Salad (sl_no, item, price)
	beverages(sl_no, item, price)
	desserts (sl_no, item, price)
	biriyani (sl_no, item, price)
	pasta (sl_no, item, price)
	roti (sl_no, item, price)
	curry (sl_no, item, price)
	chaat (sl_no, item, price)
	quesadillas (sl_no, item, price)

View:
•	view_available_rooms:  Stores room details (room, availability, price per day) – gets details from roombooking table
•	view_food_items: Stores food details (item, price, category) – gets details from all the 9 tables of food.

Procedure:
•	proc_update_availability:  This procedure updates the availability of a room after a booking is made. It decrements the room's availability by 1 to reflect the new status.
•	proc_validate_user_input: This procedure validates user input during registration, ensuring that the username, email, and password meet specific criteria (e.g., password length, email format).

Trigger:
•	trigger_user_registration:  During registration, this trigger makes sure that the email id entered does not already have a account.
•	trigger_order_deletion: This trigger logs the details of removed items from the bill to the order_deletion_log table, including the item, quantity, price, and deletion timestamp.
