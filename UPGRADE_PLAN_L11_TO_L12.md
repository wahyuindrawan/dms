# Laravel 11 → 12 Upgrade Plan untuk DMS

## Project Overview
- **Current**: Laravel 11.47.0, Filament 3.3.45, Livewire 3.7.3
- **Target**: Laravel 12+, Filament 5+, Livewire 4+
- **Scope**: 83 Filament files, 30 migrations, 10 models, 0 direct Livewire usage
- **Risk Level**: MEDIUM (Filament major version jump)

---

## Phase 1: Pre-Upgrade Preparation (Week 1)

### 1.1 Code Audit & Backup
- [ ] Backup production database
- [ ] Tag current version in Git: `git tag v11-stable`
- [ ] Create feature branch: `git checkout -b feature/upgrade-laravel-12`
- [ ] Audit Filament customizations (non-standard plugins, custom components)
- [ ] Document custom field types, actions, table configurations

### 1.2 Dependency Analysis
- [ ] Check compatibility matrix:
  - `barryvdh/laravel-dompdf` v3.1 → check L12 support
  - `maatwebsite/excel` v3.1 → check L12 support
  - `blade-ui-kit/blade-heroicons` v2.6 → check updates
  - `spatie/laravel-activitylog` v4.12.3 → v5+ (verify schema changes)
  - `spatie/laravel-permission` v6.24 → v8+ (verify schema changes)
- [ ] Test run current test suite: `php artisan test`
- [ ] Document breaking changes per dependency

### 1.3 Documentation
- [ ] Create migration checklist for each Spatie package
- [ ] Note all Filament Resource customizations
- [ ] List all service provider registrations

---

## Phase 2: Staged Dependency Upgrades (Week 2)

### 2.1 Update Laravel Framework Only
```bash
composer require laravel/framework:^12 --no-update
composer update laravel/framework --with-all-dependencies
```
- [ ] Run: `php artisan upgrade` (Laravel upgrade helper if available)
- [ ] Check `.env` for new config keys
- [ ] Run tests: `php artisan test`
- [ ] Fix framework-level breaking changes

### 2.2 Update Spatie Packages (Critical - schema migrations needed)
**Order matters** - update `laravel-permission` first:

```bash
composer require spatie/laravel-permission:^8 --no-update
composer update spatie/laravel-permission
php artisan migrate
```
- [ ] Review & run Spatie permission migrations
- [ ] Verify role/permission data integrity: `php artisan tinker`
- [ ] Test: `php artisan test`

Then `laravel-activitylog`:
```bash
composer require spatie/laravel-activitylog:^5 --no-update
composer update spatie/laravel-activitylog
php artisan migrate
```
- [ ] Review activity log schema changes
- [ ] Verify existing logs still accessible
- [ ] Test: `php artisan test`

### 2.3 Update Livewire (Dependency for Filament 5)
```bash
composer require livewire/livewire:^4 --no-update
composer update livewire/livewire
```
- [ ] Check breaking changes in component lifecycle
- [ ] Review computed properties syntax (if any custom components)
- [ ] Test: `php artisan test`

### 2.4 Update Filament Framework (Staged)
```bash
composer require filament/filament:^5 --no-update
composer update filament/*
php artisan filament:upgrade
```
- [ ] Run Filament upgrade command
- [ ] Verify all Resources still load
- [ ] Check admin panel at `/admin`
- [ ] Test CRUD operations on all resources
- [ ] Test: `php artisan test`

### 2.5 Update Supporting Packages
```bash
composer require barryvdh/laravel-dompdf:^3 maatwebsite/excel:^3 --no-update
composer update
```
- [ ] Verify PDF generation
- [ ] Verify Excel export functionality

---

## Phase 3: Code Migration (Week 2-3)

### 3.1 Filament 3 → 5 Breaking Changes
**Locations to audit**: `app/Filament/Resources/`, `app/Filament/Pages/`, `app/Filament/Widgets/`

- [ ] **Resource Structure**
  - Check if using `getRelations()` → deprecated, use `getPages()`
  - Update resource page references if needed
  
- [ ] **Field Changes**
  - Verify all form field types are still valid
  - Check TextInput, Select, DatePicker, etc. syntax
  
- [ ] **Table Configuration**
  - Verify table column definitions
  - Check filter/action syntax
  
- [ ] **Widget Updates**
  - Verify Chart widgets (DocumentsPerTypeChart, DocumentsPerYearChart)
  - Check StatsOverview widget configuration

### 3.2 Model & Migration Updates
- [ ] Add `SoftDeletes` migration timestamps if missing (Filament 5 requirement)
- [ ] Verify all models have proper relationship definitions
- [ ] Review `app/Models/Legacy/*` - consider deprecation timeline

### 3.3 Configuration Updates
- [ ] Review `config/filament.php` - merge with new defaults
- [ ] Check `config/app.php` for service provider changes
- [ ] Update `composer.json` scripts if needed

---

## Phase 4: Testing & QA (Week 3-4)

### 4.1 Unit & Feature Tests
```bash
php artisan test
php artisan test --parallel
```
- [ ] All existing tests pass
- [ ] Test authentication flow
- [ ] Test role/permission system
- [ ] Test document CRUD operations

### 4.2 Filament Admin Panel Testing
**Manual testing checklist**:
- [ ] Login page works
- [ ] Dashboard displays all widgets
- [ ] User Resource: List, Create, Edit, Delete
- [ ] Role Resource: List, Create, Edit, Delete
- [ ] Unit Resource: List, Create, Edit, Delete
- [ ] DocumentType Resource: CRUD operations
- [ ] DocumentCategory Resource: CRUD operations
- [ ] Document Resource: CRUD operations (if exists)
- [ ] Verify activity logs appear correctly
- [ ] Test permission-based access control

### 4.3 Feature Testing
- [ ] Document file preview route: `/document-file/{file}`
- [ ] PDF generation (barryvdh/laravel-dompdf)
- [ ] Excel export (maatwebsite/excel)
- [ ] Activity logging captures changes

### 4.4 Performance Testing
- [ ] Page load times acceptable
- [ ] Database query counts reasonable
- [ ] No N+1 queries in admin panel

---

## Phase 5: Deployment (Week 4)

### 5.1 Staging Environment
```bash
# On staging server
git fetch origin feature/upgrade-laravel-12
git checkout feature/upgrade-laravel-12
composer install
php artisan migrate
php artisan cache:clear
php artisan config:cache
```
- [ ] Full QA testing on staging
- [ ] Load testing if applicable
- [ ] Security audit

### 5.2 Production Deployment
```bash
# Backup production
php artisan backup:run

# Deploy
git checkout feature/upgrade-laravel-12
composer install --optimize-autoloader
php artisan down
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan up
```
- [ ] Verify application health
- [ ] Monitor error logs
- [ ] Rollback plan ready

### 5.3 Post-Deployment
- [ ] Monitor application for 24-48 hours
- [ ] Check error logs & performance metrics
- [ ] Merge PR to main branch
- [ ] Tag release: `git tag v12.0.0`

---

## Risk Mitigation Strategies

### High Risk: Filament 3 → 5 Breaking Changes
- **Mitigation**: 
  - Extensive admin panel testing before production
  - Keep staging environment up-to-date during migration
  - Document all customizations before starting

### Medium Risk: Database Schema Changes (Spatie packages)
- **Mitigation**:
  - Run migrations on backup database first
  - Test permission/role system thoroughly
  - Have rollback migration scripts ready

### Medium Risk: Third-party Package Compatibility
- **Mitigation**:
  - Test each package upgrade independently
  - Verify functionality before combining upgrades

---

## Rollback Plan

If critical issues occur:

```bash
# In case of database migration failure
php artisan migrate:rollback
php artisan migrate:rollback --step=5  # Rollback all new migrations

# In case of code issues
git revert <commit-hash>
composer require laravel/framework:^11 --no-update
composer update laravel/framework
php artisan cache:clear
```

---

## Timeline Summary

| Phase | Duration | Owner |
|-------|----------|-------|
| Pre-Upgrade Prep | 3-4 days | Dev Lead |
| Dependency Updates | 3-4 days | Backend Dev |
| Code Migration | 3-5 days | Full Team |
| Testing & QA | 4-5 days | QA + Dev |
| Deployment | 1-2 days | DevOps + Lead |
| **Total** | **~3 weeks** | - |

---

## Checklist Template

Use this for tracking progress:

```
Phase 2.1 - Laravel Framework Update
- [ ] Updated composer.json
- [ ] Ran migrations
- [ ] Tests passing
- [ ] Manual smoke tests completed

Phase 2.2 - Spatie Packages
- [ ] laravel-permission updated
- [ ] laravel-activitylog updated
- [ ] Data integrity verified
- [ ] Tests passing

Phase 3 - Code Migration
- [ ] Filament resources audited
- [ ] Breaking changes fixed
- [ ] Models updated
- [ ] Configuration merged

Phase 4 - Testing
- [ ] Unit tests: ✓
- [ ] Feature tests: ✓
- [ ] Admin panel: ✓
- [ ] Document operations: ✓

Phase 5 - Deployment
- [ ] Staging deployment: ✓
- [ ] Production ready: ✓
- [ ] Monitoring active: ✓
```

---

## Resources & References

- Laravel 11 → 12 Upgrade Guide: https://laravel.com/docs/12/upgrade
- Filament 3 → 5 Migration: https://filamentphp.com/docs/3.x
- Livewire 3 → 4 Breaking Changes: https://livewire.laravel.com/docs/upgrading
- Spatie laravel-permission releases: https://github.com/spatie/laravel-permission/releases
- Spatie laravel-activitylog releases: https://github.com/spatie/laravel-activitylog/releases

---

**Last Updated**: 2026-07-10
**Status**: Ready for Execution
