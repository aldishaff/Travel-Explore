<?php
//PopularDestinationTest.php
use PHPUnit\Framework\TestCase;

require_once './login/validation_popular_destinations.php';

class PopularDestinationTest extends TestCase
{
    public function test_required_fields()
    {
        $data = [
            'name' => '',
            'description' => '',
            'location' => '',
            'price' => '',
            'duration' => '',
            'category' => '',
            'facilities' => '',
            'rating' => ''
        ];

        $file = null;

        $errors = validate_destination_input($data, $file);

        $this->assertContains("All required fields must be filled!", $errors);
        $this->assertContains("Image upload failed!", $errors);
    }

    public function test_invalid_google_maps_url()
    {
        $data = [
            'name' => 'Test',
            'description' => 'Test Desc',
            'location' => 'Test Location',
            'price' => '100',
            'duration' => '2 Days',
            'category' => 'Beach',
            'facilities' => 'WiFi',
            'rating' => '5',
            'map_url' => 'https://facebook.com/invalid'
        ];

        $file = [
            'error' => 0,
            'type' => 'image/jpeg'
        ];

        $errors = validate_destination_input($data, $file);

        $this->assertContains("Map URL must be a valid Google Maps link!", $errors);
    }

    public function test_invalid_file_type()
    {
        $data = [
            'name' => 'Test',
            'description' => 'Test Desc',
            'location' => 'Test Location',
            'price' => '100',
            'duration' => '2 Days',
            'category' => 'Beach',
            'facilities' => 'WiFi',
            'rating' => '5'
        ];

        $file = [
            'error' => 0,
            'type' => 'image/png'
        ];

        $errors = validate_destination_input($data, $file);

        $this->assertContains("Image Only JPG!", $errors);
    }

    public function test_valid_input()
    {
        $data = [
            'name' => 'Test Name',
            'description' => 'Nice description',
            'location' => 'Good Location',
            'price' => '150',
            'duration' => '3 Days',
            'category' => 'Nature',
            'facilities' => 'Guide, Food',
            'rating' => '4',
            'map_url' => 'https://www.google.com/maps/place/Example'
        ];

        $file = [
            'error' => 0,
            'type' => 'image/jpeg'
        ];

        $errors = validate_destination_input($data, $file);

        $this->assertEmpty($errors);
    }
}