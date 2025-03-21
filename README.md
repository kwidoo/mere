`

# Kwidoo Mere

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kwidoo/mere.svg?style=flat-square)](https://packagist.org/packages/kwidoo/mere)
[![Total Downloads](https://img.shields.io/packagist/dt/kwidoo/mere.svg?style=flat-square)](https://packagist.org/packages/kwidoo/mere)
![GitHub Actions](https://github.com/kwidoo/mere/actions/workflows/main.yml/badge.svg)
Awesome — here's the updated and improved `README.md` for your `kwidoo/mere` package, including all recent enhancements:

---

**Mere = Menu + Resource**
Reusable backend core for Laravel apps with Vue frontends, providing dynamic menu definitions, resource scaffolding, and centralized configuration for forms, validation, and permissions.

---

## ✨ Features

- 🌲 **Menu definitions stored in DB** (`MenuItem`)
- 🧩 **Automatic props generation** from model structure
- 🧪 **Reusable validation rules** across Laravel + Vue
- 🔐 **Action-level permissions** (create/edit/delete)
- 🧠 **Shared base service** for clean CRUD logic
- 📦 Uses `Prettus Repository`, `Fractal`, `NestedSet`
- 🧰 Includes Artisan command for syncing menu structure

---

## ⚙️ Installation

```bash
composer require kwidoo/mere
```

### Optional config publish:

```bash
php artisan vendor:publish --provider="Kwidoo\Mere\MereServiceProvider"
```

---

## 📁 MenuItem Structure

The `MenuItem` model defines Vue frontend routes:

| Field       | Description                                                   |
| ----------- | ------------------------------------------------------------- |
| `name`      | Format: `ResourceAction` (e.g. `PropertyList`)                |
| `path`      | Vue route path (e.g. `/properties`)                           |
| `component` | Vue component name (`GenericResource`, `GenericCreate`, etc.) |
| `props`     | Configuration for fields, rules, actions, and label           |

### Example:

```json
{
  "label": "properties",
  "fields": [
    { "key": "name", "label": "Name", "sortable": true, "visible": true }
  ],
  "rules": {
    "name": "required|string|max:255"
  },
  "actions": {
    "create": true,
    "edit": true,
    "delete": false
  }
}
```

---

## 🧪 Syncing Menus via Artisan

You can auto-generate menu definitions using your model and form requests:

```bash
php artisan mere:sync-menu --resource=Property
```

This will:

- Use `App\Models\Property`'s `$fillable` and `$appends` for `fields`
- Extract rules from `PropertyCreate` and `PropertyUpdate` FormRequests
- Generate or update the following `MenuItem`s:
  - `PropertyList`
  - `PropertyCreate`
  - `PropertyUpdate`

You can override the default Vue component:

```bash
php artisan mere:sync-menu --resource=Property --component=MyCustomPage
```

---

## 🧰 BaseService

Extendable abstract service with automatic:

- Repository injection
- Validation
- CRUD event dispatching

```php
class PropertyService extends BaseService {
    protected function eventKey(): string {
        return 'property';
    }
}
```

---

## 📦 ResourceController

Generic controller for all resource endpoints. Works with:

```php
GET    /api/{resource}
GET    /api/{resource}/{id}
POST   /api/{resource}
PUT    /api/{resource}/{id}
DELETE /api/{resource}/{id}
```

Bind the service using a middleware or config map:

```php
// config/mere.php
'resources' => [
    'property' => \App\Services\PropertyService::class,
]
```

---

## 🧱 BaseRequest

Dynamic rules resolver:

```php
// POST /api/property => PropertyCreate
// PUT /api/property/{id} => PropertyUpdate
```

It pulls rules from `MenuService::getRules()` using route segments.

---

## 🧠 Component Detection

Each `MenuItem` can specify a `component` such as:

- `GenericResource` (list page)
- `GenericCreate` (create form)
- `GenericUpdate` (edit form)

This is saved as a top-level DB column and **not inside props**.

---

## 📚 API Overview

### MenuService

```php
getMenus(): Collection
getFields(string $name): array
getRules(string $name): array
```

### ResourceCollection

```php
(new ResourceCollection(...))
    ->fields([...])
    ->label('properties')
    ->canCreate(true)
```

---

## 🧪 Testing & Extensibility

- All services can be tested via Laravel’s container
- You can register custom presenters, validators, or transformers
- Easy to extend with custom menu fields, UI configs, or ACL logic

---

## 🚧 TODO

- [ ] Add granular permission checks
- [ ] Add support for `redirect`, `children` in MenuItem tree
- [ ] Sync command enhancements: guessing types, dry-run mode
- [ ] Optional: type-based component mapping

---

## 📃 License

MIT
