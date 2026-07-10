# Laravel 11 → 12 Upgrade - Pre-Flight Checklist

**Project**: DMS (Document Management System)  
**Current Version**: Laravel 11.47.0  
**Target Version**: Laravel 12+  
**Date**: 2026-07-10  
**Status**: Ready for Execution

---

## Pre-Upgrade Environment Setup

### 1. System Requirements Verification

```bash
# Check PHP version (Laravel 12 requires PHP 8.2+)
php -v
# Expected: PHP 8.2.x or higher

# Check Composer version
composer --version
# Expected: Composer 2.x

# Check available disk space
df -h
# Expected: At least 5GB free for node_modules + vendor

# Check Git status
git status
# Expected: Clean working directory
```

### 2. Environment Configuration

```bash
# Create backup of .env
cp .env .env.backup

# Verify database connection works
php artisan tinker
>>> DB::connection()->getPDO()
# Expected: PDO connection object
```

### 3. Repository Preparation

```bash
# Ensure on develop branch
git branch
# Expected: * develop

# Create feature branch for upgrade
git checkout -b feature/upgrade-laravel-12

# Tag current stable state
git tag -a v11-stable -m "Laravel 11 stable before upgrade to 12"
git push origin v11-stable
```

---

## Backup Strategy

### Database Backup

```bash
# Create SQL dump
mysqldump -u root -p dms_database > backups/dms_db_$(date +%Y%m%d_%H%M%S).sql

# Verify backup size
ls -lh backups/dms_db_*.sql

# Test restore (optional, on test database)
mysql -u root -p test_dms_database < backups/dms_db_*.sql
```

### File System Backup

```bash
# Backup storage directory
tar -czf backups/storage_$(date +%Y%m%d_%H%M%S).tar.gz storage/

# Backup composer.lock
cp composer.lock composer.lock.backup.11

# Backup config directory
tar -czf backups/config_$(date +%Y%m%d_%H%M%S).tar.gz config/
```

### Full Project Backup (Git)

```bash
# All changes committed
git add .
git commit -m "chore: final state before Laravel 12 upgrade" || echo "No changes to commit"

# Verify tag created
git tag -l | grep v11-stable
```

---

## Dependency Analysis Report

### Current Dependency Versions

| Package | Current | Target | Risk | Notes |
|---------|---------|--------|------|-------|
| `laravel/framework` | 11.47.0 | ^12 | LOW | Official upgrade path |
| `filament/filament` | 3.3.45 | ^5 | HIGH | Major version jump - extensive testing needed |
| `livewire/livewire` | 3.7.3 | ^4 | MEDIUM | Required by Filament 5 |
| `spatie/laravel-permission` | 6.24.0 | ^8 | MEDIUM | Database migrations required |
| `spatie/laravel-activitylog` | 4.12.3 | ^5 | MEDIUM | Database migrations required |
| `barryvdh/laravel-dompdf` | 3.1 | ^3 | LOW | Likely compatible |
| `maatwebsite/excel` | 3.1 | ^3 | LOW | Likely compatible |
| `blade-ui-kit/blade-heroicons` | 2.6.0 | ^2+ | LOW | May have minor updates |

### Risk Assessment Summary

**HIGH RISK** (Filament 3→5):
- UI component API changes
- Resource configuration structure changes
- Widget registration changes
- 83 Filament files potentially affected

**MEDIUM RISK** (Spatie packages):
- Database schema migrations
- Role/permission system critical
- Activity logging disruption possible

**LOW RISK** (Laravel core):
- Standard upgrade process
- Deprecation warnings available
- Official upgrade guide available

---

## Test Coverage Analysis

### Current Test Status

```bash
# Run existing tests to establish baseline
php artisan test --env=testing --parallel

# Generate coverage report
php artisan test --env=testing --coverage

# Expected: Baseline established before upgrades
```

### Critical Paths to Test Post-Upgrade

1. **Authentication**
   - User login
   - Role/permission validation
   - Session management

2. **Document Management**
   - Document CRUD operations
   - File upload/download
   - Document relationships

3. **Admin Panel**
   - All resource CRUD
   - Bulk actions
   - Filters and search

4. **Activity Logging**
   - Log creation on changes
   - Log retrieval
   - Permission tracking

5. **File Operations**
   - PDF generation
   - Excel export
   - Document preview

---

## Communication Checklist

### Stakeholder Notifications

- [ ] Team lead approval obtained
- [ ] Deployment window scheduled
- [ ] Maintenance window communicated
- [ ] Estimated downtime: 30-60 minutes
- [ ] Rollback procedure reviewed
- [ ] On-call support assigned

### Documentation Updates

- [ ] UPGRADE_PLAN_L11_TO_L12.md created
- [ ] UPGRADE_TECHNICAL_GUIDE.md created
- [ ] This checklist created
- [ ] Breaking changes documented
- [ ] Rollback procedures documented
- [ ] Testing procedures documented

---

## Go/No-Go Criteria

### Go Criteria (All must be YES)
- [ ] Database backup verified and tested
- [ ] File backups created
- [ ] Git repository clean and tagged
- [ ] All tests passing on Laravel 11
- [ ] Team approval obtained
- [ ] Maintenance window scheduled
- [ ] Rollback procedure understood
- [ ] On-call support available

### No-Go Criteria (Any YES = STOP)
- [ ] Uncommitted changes in repository
- [ ] Database backup failed
- [ ] Tests failing on current version
- [ ] Production traffic active (non-scheduled window)
- [ ] Team member unavailable for troubleshooting
- [ ] Critical issues in staging environment

---

## Day-Of Checklist

### 1 Hour Before Upgrade

- [ ] Enable maintenance mode: `php artisan down --secret=upgrade123`
- [ ] Verify backups are accessible
- [ ] Close all admin panel sessions
- [ ] Monitor error logs: `tail -f storage/logs/laravel.log`
- [ ] Team members on standby

### During Upgrade

- [ ] Execute Phase 2.1 - Laravel Framework
- [ ] Execute Phase 2.2 - Spatie packages (permission first)
- [ ] Execute Phase 2.2 - Spatie packages (activitylog)
- [ ] Execute Phase 2.3 - Livewire
- [ ] Execute Phase 2.4 - Filament
- [ ] Run migrations: `php artisan migrate`
- [ ] Clear caches: `php artisan cache:clear && php artisan config:cache`
- [ ] Exit maintenance mode: `php artisan up`

### After Upgrade

- [ ] Login to admin panel and verify it loads
- [ ] Test CRUD on each resource (User, Role, Unit, DocumentType, DocumentCategory)
- [ ] Check error logs for warnings
- [ ] Run test suite: `php artisan test`
- [ ] Monitor application for 30 minutes
- [ ] Confirm with team all systems operational

---

## Estimated Timeline

| Task | Duration | Notes |
|------|----------|-------|
| Pre-flight checks | 30 min | Verify backups, clean repo |
| Laravel framework update | 20 min | composer update + php artisan upgrade |
| Spatie packages update | 30 min | Includes database migrations |
| Livewire update | 10 min | Dependency for Filament |
| Filament update | 20 min | Includes filament:upgrade command |
| Code fixes (Filament resources) | 60 min | If breaking changes found |
| Test suite execution | 30 min | Run all tests |
| Admin panel manual testing | 30 min | CRUD operations on all resources |
| Monitoring & validation | 30 min | Check logs, verify functionality |
| **TOTAL** | **~4 hours** | Conservative estimate, may be faster |

---

## Emergency Contacts

| Role | Name | Contact |
|------|------|---------|
| Project Lead | - | - |
| DevOps/Infrastructure | - | - |
| Backend Developer | - | - |
| QA Lead | - | - |

---

## Post-Upgrade Documentation

### To Be Updated After Successful Upgrade

- [ ] README.md - Update Laravel version
- [ ] CONTRIBUTING.md - Update dev environment setup
- [ ] .github/workflows/* - Update CI/CD if needed
- [ ] docker-compose.yml - Update PHP version if needed
- [ ] Dockerfile - Update base image if needed
- [ ] CHANGELOG.md - Document upgrade notes

---

## Sign-Off

**Prepared by**: [Your Name]  
**Date**: 2026-07-10  
**Approved by**: [Lead Name]  
**Date**: [Approval Date]  

**Status**: ✅ READY FOR EXECUTION

---

**Next Action**: Schedule maintenance window and execute Phase 2.1
