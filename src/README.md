# Laravel Nova Test Assertions

A small package to aid testing Laravel Nova integrations.

### TODO
- [ ] Install guide
- [ ] Fully document examples of uses

## Installation

Require the package like any other.

```
$ composer require cristiangiordano/nova-test-assertions
```

Create a base `tests/TestResponse` class.

```php
<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestResponse as BaseTestResponse;
use NovaTestHelpers\NovaTestAssertions;

class TestResponse extends BaseTestResponse
{
    use NovaTestAssertions;
}
```

Override the `createTestResponse` method in the `TestCase` class.

```php
    protected function createTestResponse($response)
    {
        return TestResponse::fromBaseResponse($response);
    }
```

Lastly in a Nova resource test, ensure to use the `NovaTestHelpers\NovaEndpointAware` trait. 

```php
use NovaTestHelpers\NovaEndpointAware;

class ManageAdminsTest extends TestCase
{
    use NovaEndpointAware;

    public function resource(): string
    {
        return 'admins';
    }
}
```

Enjoy!

### Examples

> Note: WIP documentation

```php
 /** @test */
function viewing_a_list_of_admin_resources()
{
    $admin = factory(User::class)->state(UserRole::ADMIN)->create();
    $user  = factory(User::class)->state(UserRole::USER)->create();

    $response = $this->json('GET', $this->endpoint());

    $response->assertOk();
    $response->assertNovaCollectionLabel('Admins');
    $response->assertNovaCollectionHas($admin);
    $response->assertNovaCollectionMissing($user);
    $response->assertNovaTextField('fullname', $admin->fullname, true);
    $response->assertNovaTextField('email', $admin->email, false);
    $response->assertNovaFieldCount(2);
}
```

### API

> TODO: This section needs grouping and explaining, sorry!

```
public function assertNovaCollectionLabel(string $label)
public function assertNovaCollectionHas(Model $model)
public function assertNovaCollectionMissing(Model ...$models)
public function assertNovaFieldCount(int $count)
public function assertNovaField(string $component, array $attributes)
public function assertNovaBelongsToField(string $attribute, $value, bool $sortable = false)
public function assertNovaHasManyField(string $attribute)
public function assertNovaTextField(string $attribute, $value, bool $sortable = false)
public function assertNovaComputedField(string $name, $value, bool $sortable = false)
public function assertNovaTextareaField(string $attribute, $value, bool $sortable = false)
public function assertNovaTrixField(string $attribute, $value, bool $sortable = false)
public function assertNovaSelectField(string $attribute, $value, bool $sortable = false)
public function assertNovaMultiSelectField(string $attribute, $value)
public function assertNovaDateField(string $attribute, $value, bool $sortable = false)
public function assertNovaAvatarField(string $attribute)
public function assertNovaBooleanField(string $attribute, bool $value)
public function assertNovaSortableField(string $attribute)
public function assertNovaAdvancedMediaLibraryField(string $attribute)
public function assertNovaDateTimeField(string $attribute, $value, bool $sortable = false)
public function assertNovaMorphToField(string $attribute, $value, bool $sortable = false)
public function assertNovaActionCount(int $count)
public function assertNovaAction(string $key)
public function assertNovaMissingAction(string $key)
```