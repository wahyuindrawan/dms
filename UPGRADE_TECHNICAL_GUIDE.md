# Laravel 11 → 12 Upgrade - Detailed Technical Guide

## Quick Reference Commands

### Phase 2.1: Laravel Framework
```bash
composer require laravel/framework:^12 --no-update
composer update laravel/framework --with-all-dependencies
php artisan upgrade
php artisan test
```

### Phase 2.2: Spatie laravel-permission
```bash
composer require spatie/laravel-permission:^8 --no-update
composer update spatie/laravel-permission
php artisan migrate
php artisan test
```

### Phase 2.2: Spatie laravel-activitylog
```bash
composer require spatie/laravel-activitylog:^5 --no-update
composer update spatie/laravel-activitylog
php artisan migrate
php artisan test
```

### Phase 2.3: Livewire
```bash
composer require livewire/livewire:^4 --no-update
composer update livewire/livewire
php artisan test
```

### Phase 2.4: Filament
```bash
composer require filament/filament:^5 --no-update
composer update filament/*
php artisan filament:upgrade
# Manual testing of admin panel required
```

---

## Known Breaking Changes by Component

### Filament 3 → 5

#### Resource Changes
- Resource page routing structure updated
- `getRelations()` method deprecated → use resource configuration
- `getPages()` configuration may need updates

**Files to review**: `app/Filament/Resources/*.php`

#### Field Type Changes
Common migrations needed:
```php
// Old (Filament 3)
TextInput::make('name')->required()

// New (Filament 5) - usually compatible, but verify
TextInput::make('name')->required()

// Check for deprecated fields like:
// - Repeater → use Relations\HasMany (if applicable)
// - Wizard → verify component exists
```

**Action items**:
- [ ] `app/Filament/Resources/UserResource.php`
- [ ] `app/Filament/Resources/RoleResource.php`
- [ ] `app/Filament/Resources/UnitResource.php`
- [ ] `app/Filament/Resources/DocumentTypeResource.php`
- [ ] `app/Filament/Resources/DocumentCategoryResource.php`

#### Widget Changes
- Chart widgets API may change
- Stats widgets configuration updates

**Files to review**: 
- `app/Filament/Widgets/DocumentsPerTypeChart.php`
- `app/Filament/Widgets/DocumentsPerYearChart.php`
- `app/Filament/Widgets/StatsOverview.php`
- `app/Filament/Widgets/RecentDocumentsWidget.php`

#### Page Changes
**Files to review**:
- `app/Filament/Pages/Dashboard.php` - widget registration syntax may change
- `app/Filament/Pages/LaporanPage.php`
- `app/Filament/Pages/Auth/Login.php`

### Livewire 3 → 4

**Impact**: Low (no direct Livewire usage found in codebase)

Filament 5 uses Livewire 4 internally. If custom components exist:
- [ ] Check for `$emit()` → use `dispatch()`
- [ ] Check for `$listeners` → use `#[On(...)]` attributes
- [ ] Computed properties: `#[Computed]` attribute syntax

### Laravel 11 → 12

**Config Changes**:
- [ ] Review `config/app.php` - new service providers
- [ ] Review `config/logging.php` - new log channels
- [ ] Review `config/cache.php` - TTL handling
- [ ] New config keys in `.env`

**Framework Changes**:
- [ ] Route model binding refinements
- [ ] Middleware changes
- [ ] Exception handling updates

---

## Database Migration Strategy

### Spatie laravel-permission v6 → v8

**What changes**:
- New table structures (if using permissions)
- Column additions/modifications
- Cache invalidation needed

**Migration steps**:
1. Publish migrations: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"`
2. Review generated migrations
3. Run: `php artisan migrate`
4. Verify data integrity

**Verification**:
```php
php artisan tinker

// Check roles
>>> App\Models\Role::all();

// Check permissions
>>> App\Models\Permission::all();

// Check user roles
>>> App\Models\User::first()->roles;
```

### Spatie laravel-activitylog v4 → v5

**What changes**:
- Activity log table schema refinements
- Event logging behavior updates
- Cache key changes

**Migration steps**:
1. Publish migrations: `php artisan vendor:publish --provider="Spatie\ActivityLog\ActivityLogServiceProvider" --tag="migrations"`
2. Review generated migrations
3. Run: `php artisan migrate`
4. Verify existing logs still accessible

**Verification**:
```php
php artisan tinker

// Check activity logs exist
>>> Spatie\Activitylog\Models\Activity::count();

// Sample log entry
>>> Spatie\Activitylog\Models\Activity::latest()->first();
```

---

## Testing Strategy

### Pre-Upgrade Baseline
```bash
# Run current tests to establish baseline
php artisan test --env=testing

# Document test results
# Expected: All tests passing
```

### Post-Each-Upgrade Tests
After each major dependency upgrade:

```bash
# 1. Unit tests
php artisan test --env=testing

# 2. Feature tests
php artisan test tests/Feature --env=testing

# 3. Manual smoke tests for critical paths
php artisan tinker
>>> auth()->attempt(['email' => 'admin@example.com', 'password' => 'password'])
>>> App\Models\Document::count()
>>> App\Models\User::count()
```

### Filament Admin Panel Testing
Manual checklist after `filament:upgrade`:

```
Navigation & Layout:
- [ ] Admin panel loads at /admin
- [ ] Navigation sidebar displays all resources
- [ ] Dark/light mode toggle works (if implemented)

Authentication:
- [ ] Login page displays
- [ ] Invalid credentials rejected
- [ ] Valid login succeeds
- [ ] Logout works
- [ ] Session timeout works

User Resource (/admin/users):
- [ ] List page loads with all users
- [ ] Create new user form displays
- [ ] Create user with valid data succeeds
- [ ] Edit user page loads
- [ ] Edit user updates data
- [ ] Delete user removes from list

Role Resource (/admin/roles):
- [ ] List page loads
- [ ] Create role form works
- [ ] Permissions checkbox group displays
- [ ] Edit role saves permission changes

Unit Resource (/admin/units):
- [ ] CRUD operations work
- [ ] Relationships load correctly

DocumentType & DocumentCategory:
- [ ] CRUD operations work
- [ ] Numbering format configuration works (if editable)

Dashboard:
- [ ] All widgets load without errors
- [ ] Charts render correctly
- [ ] Stats display correct counts

Activity Logs:
- [ ] Admin > Activity Logs page loads (if accessible)
- [ ] Recent changes appear in logs
- [ ] Activity log descriptions display correctly (Indonesian text)
```

---

## Rollback Procedures

### If Composer Update Fails

```bash
# Restore from backup
git checkout composer.lock
composer install

# Or revert to Laravel 11
composer require laravel/framework:^11
composer update laravel/framework
php artisan cache:clear
```

### If Database Migration Fails

```bash
# Rollback last migration batch
php artisan migrate:rollback

# Or rollback specific migration
php artisan migrate:rollback --step=1

# Verify database state
php artisan tinker
>>> DB::table('users')->count()
```

### If Admin Panel Broken

```bash
# Clear Filament cache
php artisan filament:clear-cache

# Republish Filament assets
php artisan filament:upgrade --force

# Check for console errors in browser DevTools
# Review app/Filament logs if available
```

### Full Rollback to Laravel 11

```bash
# Revert code changes
git revert <upgrade-commit-hash>

# Restore dependencies
composer require laravel/framework:^11 filament/filament:^3 livewire/livewire:^3
composer update

# Rollback all migrations
php artisan migrate:rollback --batch=X

# Clear caches
php artisan cache:clear
php artisan config:clear
```

---

## Performance Considerations

### After Upgrade

1. **Query Performance**: Run migrations, then check query counts
```bash
# Enable query logging in config/logging.php
# Check storage/logs for excessive queries
```

2. **Asset Size**: Filament 5 may have different asset footprint
```bash
# Compare asset sizes
npm run build
du -sh public/build/
```

3. **Admin Panel Load Time**: Benchmark before/after
```bash
# Use browser DevTools or curl
curl -w "@curl-format.txt" -o /dev/null -s https://dms.local/admin
```

---

## Communication Plan

### For Team/Stakeholders

**Pre-upgrade**:
- Notify about planned maintenance window
- Estimated downtime: 30-60 minutes
- Rollback plan in place

**During upgrade**:
- Application in maintenance mode
- Monitor error logs
- Test critical paths

**Post-upgrade**:
- Confirm all systems operational
- Monitor for 48 hours
- Document any issues

---

## Troubleshooting Guide

### Issue: "Class not found" errors after upgrade

```php
// Solution 1: Clear autoloader cache
composer dump-autoload

// Solution 2: Check for renamed classes
// Review breaking changes docs for that package
```

### Issue: Filament admin panel returns 404

```php
// Solution: Check route registration
php artisan route:list | grep admin

// If routes missing, republish Filament
php artisan vendor:publish --tag=filament-config --force
php artisan filament:upgrade
```

### Issue: Database migration conflicts

```php
// Solution: Check migration status
php artisan migrate:status

// Manually verify table schemas
php artisan tinker
>>> Schema::hasTable('users')
>>> Schema::getColumns('users')
```

### Issue: Permission denied errors

```php
// Solution: Check Laravel cache
php artisan cache:clear
php artisan config:cache

// Verify filesystem permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

---

## Final Verification Checklist

Before marking upgrade complete:

- [ ] All composer packages installed without conflicts
- [ ] All database migrations completed successfully
- [ ] All unit/feature tests passing
- [ ] Admin panel fully functional (CRUD on all resources)
- [ ] Document file preview working
- [ ] PDF generation working
- [ ] Excel export working
- [ ] Activity logging working
- [ ] Permission system working
- [ ] No console errors in browser
- [ ] No PHP errors in logs
- [ ] Performance metrics acceptable
- [ ] Staging environment tested thoroughly
- [ ] Production backup verified
- [ ] Rollback procedure tested

---

**Next Steps**: Start with Phase 2.1 after team approval and maintenance window scheduled.
