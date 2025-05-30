<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InitialMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        // Tenants table
        $tenants = $this->table('tenants', ['comment' => 'Tenants table']);
        $tenants->addColumn('name', 'string', ['limit' => 255, 'null' => false, 'comment' => 'Business name (e.g., "Alex\'s Barbershop")'])
            ->addColumn('slug', 'string', ['limit' => 255, 'null' => true, 'default' => null, 'comment' => 'Optional; used for vanity URLs'])
            ->addIndex(['slug'], ['unique' => true])
            ->addColumn('phone', 'string', ['limit' => 50, 'null' => true, 'default' => null])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('address', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('city', 'string', ['limit' => 100, 'null' => true, 'default' => null])
            ->addColumn('country', 'string', ['limit' => 100, 'null' => true, 'default' => null])
            ->addColumn('postal_code', 'string', ['limit' => 20, 'null' => true, 'default' => null])
            ->addColumn('operating_hours_json', 'json', ['null' => true, 'default' => null, 'comment' => 'JSON: daily open/close hours, holidays, etc.'])
            ->addTimestamps() // adds created_at and updated_at TIMESTAMP columns
            ->create();

        // Users table
        $users = $this->table('users', ['comment' => 'Users table']);
        $users->addColumn('tenant_id', 'integer', ['null' => true, 'signed' => false, 'default' => null, 'comment' => 'Relates to specific tenant if staff; null if customer'])
            ->addColumn('name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => false])
            ->addIndex(['email'], ['unique' => true])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('phone', 'string', ['limit' => 50, 'null' => true, 'default' => null])
            ->addColumn('role', 'enum', ['values' => ['owner', 'admin', 'customer'], 'default' => 'customer', 'null' => false, 'comment' => 'User role in the system'])
            ->addTimestamps()
            ->addForeignKey('tenant_id', 'tenants', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addIndex(['tenant_id'], ['name' => 'idx_users_tenant_id'])
            ->create();

        // Services table
        $services = $this->table('services', ['comment' => 'Services table']);
        $services->addColumn('tenant_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('name', 'string', ['limit' => 255, 'null' => false, 'comment' => 'Service name (e.g., Haircut)'])
            ->addColumn('description', 'text', ['null' => true, 'default' => null])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false, 'comment' => 'Base cost of the service'])
            ->addColumn('duration_minutes', 'integer', ['null' => false, 'comment' => 'Length of service in minutes'])
            ->addColumn('is_active', 'boolean', ['default' => true, 'null' => false, 'comment' => 'Used to hide services without deleting'])
            ->addTimestamps()
            ->addForeignKey('tenant_id', 'tenants', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addIndex(['tenant_id'], ['name' => 'idx_services_tenant_id'])
            ->create();

        // Bookings table
        $bookings = $this->table('bookings', ['comment' => 'Bookings table']);
        $bookings->addColumn('tenant_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false, 'comment' => 'Customer who made the booking'])
            ->addColumn('status', 'enum', ['values' => ['pending', 'confirmed', 'canceled', 'completed'], 'default' => 'pending', 'null' => false])
            ->addColumn('start_time', 'datetime', ['null' => false])
            ->addColumn('end_time', 'datetime', ['null' => false])
            ->addColumn('total_price', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false, 'comment' => 'Total price of the booking'])
            ->addTimestamps()
            ->addForeignKey('tenant_id', 'tenants', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addIndex(['tenant_id'], ['name' => 'idx_bookings_tenant_id'])
            ->addIndex(['user_id'], ['name' => 'idx_bookings_user_id'])
            ->create();

        // Booking Services (pivot)
        $bookingServices = $this->table('booking_services', ['comment' => 'Booking Services (pivot)']);
        $bookingServices->addColumn('booking_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('service_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('service_price', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false, 'comment' => 'Snapshot of price at booking time'])
            ->addColumn('service_duration', 'integer', ['null' => false, 'comment' => 'Snapshot of duration at booking time'])
            ->addForeignKey('booking_id', 'bookings', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('service_id', 'services', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addIndex(['booking_id'], ['name' => 'idx_booking_services_booking_id'])
            ->addIndex(['service_id'], ['name' => 'idx_booking_services_service_id'])
            ->create();

        // Notifications table
        $notifications = $this->table('notifications', ['comment' => 'Notifications']);
        $notifications->addColumn('user_id', 'integer', ['null' => true, 'signed' => false, 'default' => null])
            ->addColumn('tenant_id', 'integer', ['null' => true, 'signed' => false, 'default' => null])
            ->addColumn('channel', 'enum', ['values' => ['email', 'sms', 'push'], 'null' => false, 'comment' => 'Notification channel'])
            ->addColumn('subject', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('message', 'text', ['null' => true, 'default' => null, 'comment' => 'Notification content (could be JSON)'])
            ->addColumn('is_sent', 'boolean', ['default' => false, 'null' => false])
            ->addColumn('sent_at', 'timestamp', ['null' => true, 'default' => null])
            ->addTimestamps()
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addForeignKey('tenant_id', 'tenants', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addIndex(['user_id'], ['name' => 'idx_notifications_user_id'])
            ->addIndex(['tenant_id'], ['name' => 'idx_notifications_tenant_id'])
            ->create();

        // Ratings table
        $ratings = $this->table('ratings', ['comment' => 'Ratings']);
        $ratings->addColumn('rater_user_id', 'integer', ['null' => false, 'signed' => false, 'comment' => 'User giving the rating'])
            ->addColumn('rated_user_id', 'integer', ['null' => false, 'signed' => false, 'comment' => 'User being rated'])
            ->addColumn('booking_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('rating_value', 'integer', ['limit' => 1, 'null' => false, 'comment' => 'Rating value between 1 and 5'])
            ->addColumn('rating_comment', 'text', ['null' => true, 'default' => null])
            ->addColumn('attendance_status', 'enum', ['values' => ['show', 'no_show', 'late'], 'default' => 'show', 'null' => true])
            ->addTimestamps()
            ->addForeignKey('rater_user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('rated_user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('booking_id', 'bookings', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addIndex(['rater_user_id'], ['name' => 'idx_ratings_rater_user_id'])
            ->addIndex(['rated_user_id'], ['name' => 'idx_ratings_rated_user_id'])
            ->addIndex(['booking_id'], ['name' => 'idx_ratings_booking_id'])
            ->create();

        // Add CHECK constraint for rating_value between 1 and 5 (MySQL only)
        // Phinx does not support CHECK constraints natively, so add raw SQL:
//        $this->execute('ALTER TABLE ratings ADD CONSTRAINT chk_rating_value CHECK (rating_value BETWEEN 1 AND 5)');

    }
}
