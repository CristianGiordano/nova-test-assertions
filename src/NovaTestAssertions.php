<?php

namespace NovaTestHelpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Support\Collection;

trait NovaTestAssertions
{
    public function assertNovaCollectionLabel(string $label)
    {
        return $this->assertJsonFragment(['label' => $label]);
    }

    public function assertNovaCollectionHas(Model $model)
    {
        return $this->assertJsonFragment(['value' => $model->getKey()]);
    }

    public function assertNovaCollectionMissing(Model ...$models)
    {
        foreach ($models as $model) {
            $this->assertJsonMissingExact(['value' => $model->getKey()]);
        }

        return $this;
    }

    public function assertNovaFieldCount(int $count)
    {
        PHPUnit::assertCount($count, $this->fields());

        return $this;
    }

    public function assertNovaField(string $component, array $attributes)
    {
        $original = $this->fields();

        $fields = $original->where('component', $component);

        foreach ($attributes as $key => $value) {
            $fields = $fields->where($key, $value);
        }

        $sortable = ($attributes['sortable'] ?? false) ? 'sortable' : 'non sortable';

        PHPUnit::assertCount(
            1, $fields, "Expected to find 1 {$sortable} {$component} named '{$attributes['attribute']}' but did not. All attributes: " . print_r($attributes, true) . ' all fields: ' . print_r($original->toArray(), true)
        );

        return $this;
    }

    public function assertNovaBelongsToField(string $attribute, $value, bool $sortable = false)
    {
        return $this->assertNovaField('belongs-to-field', compact('attribute', 'value', 'sortable'));
    }

    public function assertNovaHasManyField(string $attribute)
    {
        return $this->assertNovaField('has-many-field', compact('attribute'));
    }

    public function assertNovaTextField(string $attribute, $value, bool $sortable = false)
    {
        return $this->assertNovaField('text-field', compact('attribute', 'value', 'sortable'));
    }

    public function assertNovaComputedField(string $name, $value, bool $sortable = false)
    {
        $attribute = 'ComputedField';

        return $this->assertNovaField('text-field', compact('attribute', 'name', 'value', 'sortable'));
    }

    public function assertNovaTextareaField(string $attribute, $value, bool $sortable = false)
    {
        return $this->assertNovaField('textarea-field', compact('attribute', 'value', 'sortable'));
    }

    public function assertNovaTrixField(string $attribute, $value, bool $sortable = false)
    {
        return $this->assertNovaField('trix-field', compact('attribute', 'value', 'sortable'));
    }

    public function assertNovaSelectField(string $attribute, $value, bool $sortable = false)
    {
        return $this->assertNovaField('select-field', compact('attribute', 'value', 'sortable'));
    }

    public function assertNovaMultiSelectField(string $attribute, $value)
    {
        return $this->assertNovaField('multiselect-field', compact('attribute', 'value'));
    }

    public function assertNovaDateField(string $attribute, $value, bool $sortable = false)
    {
        return $this->assertNovaField('date', compact('attribute', 'value', 'sortable'));
    }

    public function assertNovaAvatarField(string $attribute)
    {
        return $this->assertNovaField('file-field', compact('attribute'));
    }

    public function assertNovaBooleanField(string $attribute, bool $value)
    {
        $this->assertNovaField('boolean-field', compact('attribute', 'value'));
    }

    public function assertNovaSortableField(string $attribute)
    {
        $this->assertNovaField('nova-field-sortable', compact('attribute'));
    }

    public function assertNovaAdvancedMediaLibraryField(string $attribute)
    {
        $this->assertNovaField('advanced-media-library-field', compact('attribute'));
    }

    public function assertNovaDateTimeField(string $attribute, $value, bool $sortable = false)
    {
        return $this->assertNovaField('date-time', compact('attribute', 'value', 'sortable'));
    }

    public function assertNovaMorphToField(string $attribute, $value, bool $sortable = false)
    {
        return $this->assertNovaField('morph-to-field', compact('attribute', 'value', 'sortable'));
    }

    /**
     * @return Collection
     */
    protected function fields()
    {
        $decoded = $this->decodeResponseJson();

        $key = array_key_exists('resource', $decoded) ? 'resource.fields' : 'resources.0.fields';

        return collect(data_get($decoded, $key));
    }

    public function assertNovaActionCount(int $count)
    {
        return $this->assertJsonCount($count, 'actions');
    }

    public function assertNovaAction(string $key)
    {
        return $this->assertJsonFragment(['uriKey' => $key]);
    }

    public function assertNovaMissingAction(string $key)
    {
        return $this->assertJsonMissingExact(['uriKey' => $key]);
    }
}