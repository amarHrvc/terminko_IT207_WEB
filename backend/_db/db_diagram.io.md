// Booking System ERD for dbdiagram.io

// Tenants table - Stores information about registered businesses
Table tenants {
  id uuid [pk, note: "Unique identifier (UUID or auto-increment)"]
  name varchar [note: "Business name (e.g., 'Alex's Barbershop')"]
  slug varchar [note: "Optional; used for vanity URLs"]
  phone varchar
  email varchar
  address varchar
  city varchar
  country varchar
  postal_code varchar
  // time_zone varchar [note: "To manage booking schedules per tenant's location"]
  operating_hours_json json [note: "JSON to store daily open/close hours, holidays, etc."]
  created_at timestamp
  updated_at timestamp
}

// Users table - Stores all users who can login
Table users {
  id uuid [pk, note: "Unique user identifier"]
  tenant_id uuid [ref: > tenants.id, note: "Relates to specific tenant if staff; null if customer"]
  name varchar
  email varchar
  password_hash varchar
  phone varchar [note: "Optional contact number for SMS notifications"]
  role varchar [note: "e.g., 'owner', 'staff', 'customer'"]
  created_at timestamp
  updated_at timestamp
}
// Services table - Defines services each tenant offers
Table services {
  id uuid [pk, note: "Unique service identifier"]
  tenant_id uuid [ref : > tenants.id, note: "Links service to specific tenant"]
  name varchar [note: "e.g., 'Haircut', 'Car Wash', 'Nail Polish'"]
  description text
  price decimal [note: "Base cost of the service"]
  duration_minutes integer [note: "Length of service in minutes (15, 30, 45, etc.)"]
  is_active boolean [note: "Boolean to 'hide' services without deleting them"]
  created_at timestamp
  updated_at timestamp
}


// Bookings table - Holds appointment data
Table bookings {
  id uuid [pk, note: "Unique booking identifier"]
  tenant_id uuid [ref: > tenants.id, note: "The tenant/business where booking is made"]
  user_id uuid [ref: > users.id, note: "The customer who made the booking"]
  status varchar [note: "e.g., 'pending', 'confirmed', 'canceled', 'completed'"]
  start_time timestamp [note: "Scheduled start datetime"]
  end_time timestamp [note: "Scheduled end datetime"]
  total_price decimal [note: "Aggregated cost if multiple services booked"]
  created_at timestamp
  updated_at timestamp
}

// Booking Services pivot table - Allows booking to include multiple services
Table booking_services {
  id uuid [pk, note: "Pivot record identifier"]
  // staff_id uuid [ref: > users.id, note: "Optional; if specific staff member assigned"]
  booking_id uuid [ref: > bookings.id]
  service_id uuid [ref: > services.id]
  service_price decimal [note: "Snapshot of service price at booking time"]
  service_duration integer [note: "Snapshot of service duration at booking time"]
}

// Staff Schedules table - Optional for staff working hours
// Table staff_schedules {
//   id uuid [pk, note: "Unique identifier"]
//   staff_id uuid [ref: > users.id, note: "References the staff user"]
//   day_of_week integer [note: "0-6 (Sunday to Saturday)"]
//   start_time time
//   end_time time
//   is_day_off boolean [note: "Boolean to mark rest days, holidays, or sick days"]
// }

// Notifications table - Optional for tracking notifications
Table notifications {
  id uuid [pk, note: "Unique identifier"]
  user_id uuid [ref: > users.id, note: "User to receive notification"]
  tenant_id uuid [ref: > tenants.id, note: "If tenant-triggered notification"]
  channel varchar [note: "e.g., 'email', 'sms', 'push'"]
  subject varchar [note: "Short title or reason"]
  message text [note: "Notification body (text or JSON)"]
  is_sent boolean [note: "Boolean to mark if notification was successfully dispatched"]
  sent_at timestamp
  created_at timestamp
  updated_at timestamp
}

// Ratings table - Captures feedback between users and service providers
Table ratings {
  id uuid [pk, note: "Unique rating record"]
  rater_user_id uuid [ref: > users.id, note: "The user giving the rating (staff or customer)"]
  rated_user_id uuid [ref: > users.id, note: "The user/entity being rated (customer or staff/owner)"]
  booking_id uuid [ref: > bookings.id, note: "Ties the rating to a completed booking"]
  rating_value integer [note: "A numeric score (e.g., 1-5)"]
  rating_comment text [note: "Optional user-submitted feedback"]
  attendance_status varchar [note: "e.g., 'show', 'no show', 'late'"]
  created_at timestamp
  updated_at timestamp
}

// // Audit Logs table - Optional for robust auditing
// Table audit_logs {
//   id uuid [pk, note: "Unique log record"]
//   user_id uuid [ref: > users.id, note: "Who performed the action"]
//   table_name varchar [note: "e.g., 'services', 'bookings'"]
//   record_id uuid [note: "Which record was changed"]
//   action varchar [note: "'create', 'update', 'delete'"]
//   changes_json json [note: "What changed (before/after snapshots)"]
//   created_at timestamp
// }