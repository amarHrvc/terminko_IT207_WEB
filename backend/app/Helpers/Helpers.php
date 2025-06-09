<?php

namespace App\Helpers;

use App\Dao\BookingDao;
use App\Dao\UserDao;
use App\Models\Enums\UserRole;
use Faker\Extension\Helper;
use Faker\Factory;
use App\Dao\TenantDao;
use Flight;
use phpDocumentor\Reflection\Types\Boolean;

class Helpers
{
    /**
     * @param mixed $result
     * @return void
     */
    public static function testOutput(mixed $result): void
    {
        fwrite(STDERR, print_r($result, true));
    }


    /**
     * @param \Faker\Generator|null $faker
     * @return int
     */
    public static function getTenantId(\Faker\Generator $faker): int
    {
        $tenantData = self::getTenantData($faker);

        return new TenantDao()->create($tenantData);
    }

    /**
     * @param \Faker\Generator|null $faker
     * @return int
     */
    public static function getUserId(\Faker\Generator $faker): int
    {
        $tenantData = self::getUserData($faker);
        return new UserDao()->create($tenantData);
    }

    /**
     * @param \Faker\Generator|null $faker
     * @return int
     */
    public static function getBookingId(\Faker\Generator $faker): int
    {
        $bookingData = self::getBookingData($faker);

        return new BookingDao()->create($bookingData);
    }


    public static function getTenantData(\Faker\Generator $faker): array
    {
        return [
            'name' => "Posao: " . $faker->randomNumber(2,false),
            'slug' => $faker->unique()->slug(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->unique()->companyEmail(),
            'address' => $faker->streetAddress(),
            'city' => "Sarajevo",
            'country' => "BA",
            'postal_code' => "71000",
            'operating_hours_json' => json_encode(['open' => '08:00', 'close' => '18:00']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    public static function getUserData(\Faker\Generator $faker, bool $admin = false): array
    {
        $faker = Factory::create();
        $pasword = $admin ? 'admin123' : 'secret123';
        $name = $admin ? 'admin' : $faker->name();
        $email = $admin ? 'admin@test.com' : $faker->unique()->safeEmail();
        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $pasword,
            'role' => $admin ? UserRole::ADMIN->value : UserRole::CUSTOMER->value,
        ];

        return $userData;
    }


    public static function getRatingData(\Faker\Generator $faker): array
    {
        $id1 = Helpers::getUserId($faker);
        $faker = Factory::create();
        $id2 = Helpers::getUserId($faker);
        $bookingId = Helpers::getBookingId($faker);

        return [
            'rater_user_id' => $id1,
            'rated_user_id' => $id2,
            'booking_id' =>  $bookingId,
            'rating_value' => $faker->numberBetween(1, 5),
            'rating_comment' => $faker->randomElement([10, 15, 30]),
            'attendance_status' => true
        ];
    }


    /**
     * @param \Faker\Generator|null $faker
     * @return array
     */
    public static function getServiceData(\Faker\Generator $faker): array
    {
        return [
            'tenant_id' => Helpers::getTenantId($faker),
            'name' => $faker->name(),
            'description' => $faker->words(11, true),
            'price' => $faker->randomFloat(2, 10, 50),
            'duration_minutes' => $faker->randomElement([10, 15, 30]),
            'is_active' => true
        ];
    }



    public static function getBookingData(\Faker\Generator $faker): array
    {
        // Create realistic start and end times
        $start = $faker->dateTimeBetween('+1 hour', '+2 days');
        $end = (clone $start)->modify('+1 hour');

        return [
            'tenant_id' => self::getTenantId($faker),
            'user_id' => self::getUserId($faker),
            'status' => $faker->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
            'start_time' => $start->format('Y-m-d H:i:s'),
            'end_time' => $end->format('Y-m-d H:i:s'),
            'total_price' => $faker->randomFloat(2, 20, 150),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }

    public static function createTestTenantData(): int
    {
        return new TenantDao()->create(self::getTenantData(Factory::create()));
    }

    /**
     * @param array $service
     * @return void
     */
    public static function checkTenantId($tenant): void
    {
//    die ($tenant['id']);
//    echo ($tenant['id'] != Flight::get('user')->getTenantId());
//    die(Flight::get('user')->getId());
        if ($tenant['id'] != Flight::get('user')->getTenantId()) {
            Flight::jsonHalt(["error" => "User does not have access to this service (Tenant ID missmatch) !"], 404);
        }
    }

    public static function jsonResponse(bool $success, string $message, $data = null, int $statusCode = 200): void
    {
        $response = self::getResponseObject([
            'success' => $success,
            'message' => $message,
            'error' => !$success ? true : false,
        ], $statusCode);
  

        if ($data !== null) {
            $response['data'] = $data;
        }

        Flight::json($response, $statusCode, JSON_PRETTY_PRINT);
    }
    

    public static function getResponseObject(array $data, int $statusCode): array
    {
        return  $response = [
            'success' => $data['success'] ?? false,
            'error' => !$data['success'] ?? false,
            'message' => $data['message'] ?? '',
            'code' => $statusCode

        ];
    }

    public static function jsonResponseFromObject($data, int $statusCode = 200): void
    {
        $response = [
            'success' => "",
            'error' => "",
            'message' => "",
            'code' => $statusCode
        ];

        if ($data !== null) {
            $response = $data;
            $response['code'] = $statusCode;
        }

        Flight::json($response, $statusCode, JSON_PRETTY_PRINT);
    }
}
