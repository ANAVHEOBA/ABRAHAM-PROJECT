Full API Documentation for Frontend Integration
General Notes: - Base URL: All routes are prefixed with ‘/api‘ (e.g., ‘http://your-domain.com/api/register‘). - Authentication: Most routes require authentication using Laravel Sanctum. The frontend must send an ‘Authorization‘ header with a Bearer token for authenticated routes. - Response Format: All responses are in JSON format, with a status code indicating success (200, 201) or error (400, 401, 404, etc.).
1. Public Routes These routes are accessible without authentication.

    GET /api/data - Description: Fetches general data. - Response: JSON with the requested data.
    POST /api/register - Description: Registers a new user. - Request Body: “‘json "name": "string", "email": "string", "password": "string" “‘ - Response: JSON containing the user details and a token.
    POST /api/login - Description: Logs in a user. - Request Body: “‘json "email": "string", "password": "string" “‘ - Response: JSON containing the user details and a token.

2. Authenticated Routes These routes require an authentication token (Bearer token).

    GET /api/user - Description: Fetches the currently authenticated user’s details. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with user details.
    POST /api/logout - Description: Logs out the authenticated user. - Headers: ‘Authorization: Bearer token‘ - Response: JSON confirming the logout.

3. Ticket Management Handles creating, updating, and viewing support tickets.

    GET /api/tickets - Description: Fetches all tickets. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of tickets.
    POST /api/tickets - Description: Creates a new ticket. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "title": "string", "description": "string" “‘ - Response: JSON with the created ticket details.
    POST /api/tickets/ticket/assign - Description: Assigns a ticket to a user. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "user_id": "integer" “‘ - Response: JSON with updated ticket details.
    POST /api/tickets/ticket/comments - Description: Adds a comment to a ticket. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "comment": "string" “‘ - Response: JSON with the comment details.
    DELETE /api/comments/comment - Description: Deletes a specific comment. - Headers: ‘Authorization: Bearer token‘ - Response: JSON confirming deletion.

4. Notifications Manages notifications for users.

    GET /api/notifications - Description: Fetches all notifications for the authenticated user. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of notifications.
    POST /api/notifications - Description: Creates a new notification. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "title": "string", "message": "string" “‘ - Response: JSON with the created notification details.
    PUT /api/notifications/notification - Description: Updates a notification. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "title": "string", "message": "string" “‘ - Response: JSON with updated notification details.

5. Delivery Management Routes for managing delivery data.

    GET /api/deliveries - Description: Fetches all deliveries. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of deliveries.
    GET /api/deliveries/delivery - Description: Fetches details for a specific delivery. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with delivery details.
    PUT /api/deliveries/delivery - Description: Updates a specific delivery. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "status": "string", "remarks": "string" “‘ - Response: JSON with updated delivery details.

6. Analytics and User Activity Provides endpoints for fetching analytics and user activity trends.

    GET /api/analytics/overview - Description: Fetches analytics overview. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with analytics data.
    GET /api/user-activity/trends - Description: Fetches user activity trends. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with activity trends.
    GET /api/user-activity/daily - Description: Fetches daily user activity. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with daily activity data.
    GET /api/order-trends/daily - Description: Fetches daily order trends. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with daily order trends.

7. User Management For managing user accounts and profiles.

    GET /api/users - Description: Fetches all users. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of users.
    GET /api/users/user - Description: Fetches details of a specific user. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with user details.
    PUT /api/users/user - Description: Updates user information. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "name": "string", "email": "string", "role": "string" “‘ - Response: JSON with updated user details.
    POST /api/users/user/deactivate - Description: Deactivates a user account. - Headers: ‘Authorization: Bearer token‘ - Response: JSON confirming deactivation.
    POST /api/users/user/activate - Description: Activates a user account. - Headers: ‘Authorization: Bearer token‘ - Response: JSON confirming activation.

8. Pilot Management For assigning and reviewing pilot performance.

    POST /api/assign-pilot - Description: Assigns a pilot to a task. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "pilot_id": "integer", "task_id": "integer" “‘ - Response: JSON with assignment details.
    GET /api/pilot-performances - Description: Fetches all pilot performances. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of performances.
    GET /api/pilot-performances/pilot - Description: Fetches performance details of a specific pilot. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with performance details.
    POST /api/pilot-performances - Description: Stores performance data for a pilot. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "pilot_id": "integer", "score": "integer", "remarks": "string" “‘ - Response: JSON with stored performance data.

9. Transaction Management Handling transactions involving users and pilots.

    GET /api/transactions - Description: Fetches all transactions. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of transactions.
    GET /api/transactions/transaction - Description: Fetches details of a specific transaction. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with transaction details.
    POST /api/transactions - Description: Creates a new transaction. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "user_id": "integer", "amount": "float", "description": "string" “‘ - Response: JSON with the created transaction details.
    GET /api/users/user/transactions - Description: Fetches transactions related to a specific user. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of user transactions.
    GET /api/pilots/pilot/transactions - Description: Fetches transactions related to a specific pilot. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of pilot transactions.

10. Admin Notifications Managing notifications specific to admin users.

    POST /api/admin/notifications - Description: Sends a notification to admin users. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "title": "string", "message": "string" “‘ - Response: JSON with notification details.
    GET /api/admin/notifications - Description: Fetches notifications for admin users. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of admin notifications.
    POST /api/admin/notifications/id/read - Description: Marks an admin notification as read. - Headers: ‘Authorization: Bearer token‘ - Response: JSON confirming the notification is marked as read.

11. Admin Routes These routes are throttled to limit the number of requests.

    POST /api/admin/login - Description: Logs in an admin user. - Request Body: “‘json "email": "string", "password": "string" “‘ - Response: JSON with admin details and token.
    POST /api/admin/logout - Description: Logs out the admin user. - Headers: ‘Authorization: Bearer token‘ - Response: JSON confirming the logout.
    POST /api/admin/settings - Description: Updates system settings. - Headers: ‘Authorization: Bearer token‘ - Request Body: JSON with settings data. - Response: JSON confirming the update.
    POST /api/admin/content - Description: Updates application content. - Headers: ‘Authorization: Bearer token‘ - Request Body: JSON with content data. - Response: JSON confirming the update.

12. Versioned API Routes (v1) These routes handle version-specific logic and are throttled.

    GET /api/v1/orders - Description: Fetches all orders. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of orders.
    GET /api/v1/orders/order - Description: Fetches details for a specific order. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with order details.
    POST /api/v1/orders/search - Description: Searches for orders. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "criteria": "string" “‘ - Response: JSON list of orders matching the search criteria.

13. Payout Management Routes for managing payouts to users and pilots.

    GET /api/payouts - Description: Fetches all payouts. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of payouts.
    GET /api/payouts/payout - Description: Fetches details of a specific payout. - Headers: ‘Authorization: Bearer token‘ - Response: JSON with payout details.
    POST /api/payouts/initiate - Description: Initiates a payout process. - Headers: ‘Authorization: Bearer token‘ - Request Body: “‘json "user_id": "integer", "amount": "float" “‘ - Response: JSON confirming the initiation of payout.
    GET /api/pilots/pilot/payouts - Description: Fetches all payouts related to a specific pilot. - Headers: ‘Authorization: Bearer token‘ - Response: JSON list of pilot payouts.
