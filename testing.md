# Testing Documentation - Jaya Express Laravel Project

## Overview

This document outlines the testing strategy, setup, and best practices for the Jaya Express Laravel application. The project uses PHPUnit for testing with both Unit and Feature tests.

## Testing Setup

### Prerequisites
- PHP 8.1+
- Laravel 11+
- PHPUnit 10+
- Composer dependencies installed

### Configuration Files
- `phpunit.xml` - PHPUnit configuration for test execution
- `tests/` - Main testing directory
  - `Feature/` - Feature/Integration tests
  - `Unit/` - Unit tests
  - `TestCase.php` - Base test case class

## Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Run only Feature tests
php artisan test tests/Feature

# Run only Unit tests
php artisan test tests/Unit

# Run specific test file
php artisan test tests/Feature/YourTestFile.php
```

### Run Tests with Coverage
```bash
php artisan test --coverage
```

### Run Tests in Parallel
```bash
php artisan test --parallel
```

## Test Structure

### Feature Tests
Located in `tests/Feature/`, these tests validate:
- HTTP request/response flows
- API endpoints
- Database interactions
- Authentication & authorization
- Business logic workflows

Example: Testing the Invoice, Pengiriman (Delivery), and Driver modules

### Unit Tests
Located in `tests/Unit/`, these tests validate:
- Individual model methods
- Helper functions
- Service classes
- Business logic in isolation

## Key Models to Test

### 1. **PengirimanModel** (Delivery)
- Creation and updates
- Status transitions
- Relations with Invoice, Driver, Armada
- Foto Pengiriman associations
- Signature verification

### 2. **InvoiceModel**
- Invoice generation
- Payment tracking
- Detail line items (InvoiceDetailModel)
- Status management

### 3. **DriverModel**
- Driver information management
- Armada assignments
- Delivery history
- License/documentation tracking

### 4. **ArmadaModel**
- Vehicle management
- Driver assignments
- Status tracking
- Photo documentation

### 5. **User Model**
- Authentication
- Role management (added via 2026_05_05_030219_add_role_to_users.php migration)
- Authorization checks

### 6. **FotoPengirimanModel**
- Photo uploads for deliveries
- Image storage verification
- Association with Pengiriman

### 7. **SignatureModel**
- Delivery signature capture
- Verification
- Timestamp tracking

## Test Writing Guidelines

### 1. Test Naming Convention
```php
// Format: testActionUnderConditionWithExpectedResult
public function testCreateInvoiceWithValidData()
public function testUpdatePengirimanStatusToDelivered()
public function testRejectDriverWithoutRequiredDocuments()
```

### 2. Arrange-Act-Assert Pattern
```php
public function testInvoiceCalculation()
{
    // Arrange
    $invoice = Invoice::factory()->create(['total' => 100000]);
    $detail = InvoiceDetail::factory()->create(['invoice_id' => $invoice->id]);
    
    // Act
    $result = $invoice->calculateTotal();
    
    // Assert
    $this->assertEquals(100000, $result);
}
```

### 3. Database Testing
```php
// Use transactions to rollback after each test
public function testCreatePengiriman()
{
    // Create test data
    $pengiriman = Pengiriman::factory()->create();
    
    // Assert it exists
    $this->assertDatabaseHas('pengiriman', [
        'id' => $pengiriman->id,
        'status' => 'pending'
    ]);
}
```

### 4. Authentication Testing
```php
public function testUnauthorizedAccessForbidden()
{
    $response = $this->get('/api/invoices');
    $response->assertStatus(401);
}

public function testAuthorizedUserCanAccess()
{
    $user = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($user)->get('/api/invoices');
    $response->assertStatus(200);
}
```

## Testing Key Features

### Invoice Management
- [ ] Create invoice with line items
- [ ] Update invoice status
- [ ] Calculate invoice totals
- [ ] Validate payment status
- [ ] Export invoice data

### Delivery Management (Pengiriman)
- [ ] Create delivery order
- [ ] Update delivery status (pending → in_transit → delivered)
- [ ] Assign driver to delivery
- [ ] Assign armada to delivery
- [ ] Add delivery photos
- [ ] Capture signature
- [ ] Calculate delivery metrics

### Driver Management
- [ ] Create driver profile
- [ ] Update driver information
- [ ] Assign armada to driver
- [ ] Track driver deliveries
- [ ] Validate driver documents

### Vehicle Management (Armada)
- [ ] Register vehicle
- [ ] Upload vehicle photos
- [ ] Assign to driver
- [ ] Track vehicle status
- [ ] Maintenance records

### Photo Management
- [ ] Upload delivery photos
- [ ] Associate photos with delivery
- [ ] Retrieve photo URLs
- [ ] Delete photos

### Signature Management
- [ ] Capture signature
- [ ] Validate signature data
- [ ] Associate with delivery
- [ ] Retrieve signature

## Continuous Integration

### Pre-commit Testing
Before committing code, run:
```bash
php artisan test
```

### CI/CD Pipeline
Tests should run automatically on:
- Push to main branch
- Pull requests
- Before deployment

## Debugging Tests

### Run with Verbose Output
```bash
php artisan test --verbose
```

### Run Single Test
```bash
php artisan test tests/Feature/YourTest.php --filter testMethodName
```

### Stop on First Failure
```bash
php artisan test --stop-on-failure
```

### Debug with Log Output
```bash
// In test file
Log::info('Debug message', $data);
php artisan test --verbose
```

## Common Testing Issues

### Issue: Tests fail due to database
**Solution**: Ensure test database is configured in `.env.testing`

### Issue: Authentication not working
**Solution**: Use `$this->actingAs($user)` or create test user with factory

### Issue: File upload tests fail
**Solution**: Use Storage::fake('storage_disk') for file operations

### Issue: Time-dependent tests fail
**Solution**: Use Carbon::setTestNow() to freeze time

## Best Practices

1. **Isolation**: Each test should be independent
2. **Clarity**: Test names should be descriptive
3. **Speed**: Keep tests fast by mocking external calls
4. **Coverage**: Aim for 80%+ code coverage on critical paths
5. **Documentation**: Comment complex test logic
6. **DRY**: Use setUp() and factories to avoid repetition
7. **Real Data**: Use factories and seeders for realistic test data
8. **Performance**: Use in-memory SQLite for feature tests if needed

## Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Factories](https://laravel.com/docs/eloquent-factories)
- [Laravel Mocking](https://laravel.com/docs/mocking)

## Contact

For testing questions or additions, contact the development team.

---

**Last Updated**: May 17, 2026
