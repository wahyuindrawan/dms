# Laravel 11 → 12 Upgrade - Quick Reference Card

**Print this page for quick access during upgrade execution**

---

## 🎯 At a Glance

| Item | Value |
|------|-------|
| **Current Version** | Laravel 11.47.0 |
| **Target Version** | Laravel 12+ |
| **Timeline** | ~3 weeks |
| **Risk Level** | MEDIUM (Filament major) |
| **Downtime** | 30-60 min |
| **Team Size** | 4-5 people |

---

## 📋 Pre-Execution Checklist

- [ ] Database backup created & verified
- [ ] File system backup created
- [ ] Git feature branch created: `feature/upgrade-laravel-12`
- [ ] Tests passing on Laravel 11
- [ ] Team on standby
- [ ] Maintenance window scheduled
- [ ] Rollback procedure tested

---

## ⚡ Quick Commands Reference

### Phase 2.1: Laravel Framework
```bash
composer require laravel/framework:^12 --no-update
composer update laravel/framework --with-all-dependencies
php artisan upgrade
php artisan cache:clear
php artisan config:clear
php artisan test
```

### Phase 2.2a: Spatie laravel-permission
```bash
composer require spatie/laravel-permission:^8 --no-update
composer update spatie/laravel-permission
php artisan migrate
php artisan cache:clear
php artisan test
```

### Phase 2.2b: Spatie laravel-activitylog
```bash
composer require spatie/laravel-activitylog:^5 --no-update
composer update spatie/laravel-activitylog
php artisan migrate
php artisan cache:clear
php artisan test
```

### Phase 2.3: Livewire
```bash
composer require livewire/livewire:^4 --no-update
composer update livewire/livewire
php artisan cache:clear
php artisan test
```

### Phase 2.4: Filament
```bash
composer require filament/filament:^5 --no-update
composer update filament/*
php artisan filament:upgrade
npm run build
php artisan cache:clear
php artisan test
```

### Post-Upgrade Cleanup
```bash
php artisan migrate
php artisan cache:clear
php artisan config:cache
php artisan test
```

---

## 🧪 Testing Quick Checklist

### Critical Path Tests

**Authentication**
- [ ] User login works
- [ ] Invalid credentials rejected
- [ ] Logout works

**Admin Panel CRUD**
- [ ] User Resource: List, Create, Edit, Delete
- [ ] Role Resource: List, Create, Edit, Delete
- [ ] Unit Resource: CRUD works
- [ ] DocumentType Resource: CRUD works
- [ ] DocumentCategory Resource: CRUD works

**Key Features**
- [ ] Document upload/download
- [ ] Document preview (route: `/document-file/{file}`)
- [ ] PDF generation
- [ ] Excel export
- [ ] Activity logging works
- [ ] Permission system enforces

**Admin Panel**
- [ ] Dashboard loads
- [ ] All widgets display
- [ ] Charts render
- [ ] Navigation works
- [ ] No console errors

---

## 🚨 Emergency Commands

### If Filament Admin Breaks
```bash
php artisan filament:clear-cache
php artisan filament:upgrade --force
php artisan cache:clear
```

### If Migrations Fail
```bash
php artisan migrate:rollback
php artisan migrate:rollback --step=1
```

### Full Rollback to Laravel 11
```bash
git revert <commit-hash>
composer require laravel/framework:^11 filament/filament:^3 livewire/livewire:^3
composer update
php artisan migrate:rollback --batch=X
php artisan cache:clear
```

---

## 📊 Verification Queries

### Check Database State
```bash
php artisan tinker

# Check user count
>>> App\Models\User::count()

# Check role count
>>> App\Models\Role::count()

# Check permission count
>>> App\Models\Permission::count()

# Check activity logs
>>> Spatie\Activitylog\Models\Activity::count()

# Check recent activity
>>> Spatie\Activitylog\Models\Activity::latest()->first()
```

---

## 🔴 HIGH RISK Areas

| Area | Risk | Mitigation |
|------|------|-----------|
| **Filament 3→5** | Breaking changes | Extensive admin testing |
| **Role/Permission** | System critical | Verify after migration |
| **Activity Logs** | Data loss possible | Backup before migration |
| **Production Deploy** | Downtime | Scheduled window |

---

## ⏱️ Timeline Estimate

| Phase | Duration | Owner |
|-------|----------|-------|
| Pre-flight | 30 min | DevOps |
| Phase 2.1 (Laravel) | 20 min | Backend |
| Phase 2.2 (Spatie) | 30 min | Backend |
| Phase 2.3 (Livewire) | 10 min | Backend |
| Phase 2.4 (Filament) | 20 min | Backend |
| Code fixes | 60 min | Backend |
| Tests & verification | 30 min | QA |
| Admin panel testing | 30 min | QA |
| Staging deploy | 30 min | DevOps |
| **TOTAL** | **~4 hours** | Team |

---

## 📞 Escalation

**Issue During Execution**:
1. Check `UPGRADE_TECHNICAL_GUIDE.md` troubleshooting
2. Contact on-call engineer
3. Escalate to Project Lead if blocking

**Decision Needed**:
→ Project Lead

**Technical Question**:
→ Backend Lead

**Environment Issue**:
→ DevOps Lead

---

## ✅ Success Criteria

All must be YES:
- [ ] Composer packages installed without conflicts
- [ ] All migrations successful
- [ ] All tests passing
- [ ] Admin panel loads
- [ ] CRUD operations work
- [ ] Documents operations work
- [ ] No PHP errors in logs
- [ ] No console errors

---

## 📝 During Execution

**Fill in UPGRADE_EXECUTION_LOG.md with:**
- Start time for each phase
- Command output
- Issues encountered
- Fixes applied
- End time for each phase
- Test results

---

## 🎓 Key Documents

| Document | When to Use |
|----------|-----------|
| `README_UPGRADE.md` | Need overview |
| `UPGRADE_PLAN_L11_TO_L12.md` | Need full strategy |
| `UPGRADE_TECHNICAL_GUIDE.md` | Need technical details |
| `UPGRADE_SUMMARY.md` | Need quick reference |
| `UPGRADE_EXECUTION_LOG.md` | During execution |

---

## 📍 Common Issues & Fixes

### "Class not found" after upgrade
```bash
composer dump-autoload
```

### Filament admin returns 404
```bash
php artisan vendor:publish --tag=filament-config --force
php artisan filament:upgrade
```

### Permission denied errors
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
php artisan cache:clear
```

### Database migration conflicts
```bash
php artisan migrate:status
php artisan migrate
```

---

## 🚀 Go/No-Go Decision

### GO if:
✅ All backups verified  
✅ Tests passing  
✅ Team ready  
✅ Window available  
✅ Rollback tested  

### NO-GO if:
❌ Uncommitted changes  
❌ Backup failed  
❌ Tests failing  
❌ Critical production issue  
❌ Team member unavailable  

---

## 📞 Contact

| Role | When | Action |
|------|------|--------|
| Backend Dev | Issue during upgrade | Troubleshoot |
| DevOps | Environment issues | Investigate |
| QA Lead | Testing questions | Consult |
| Project Lead | Decision needed | Escalate |

---

## 💾 Backup Locations

- Database: `backups/dms_db_*.sql`
- Files: `backups/storage_*.tar.gz`
- Config: `backups/config_*.tar.gz`
- Composer: `composer.lock.backup.11`

---

## 🎯 End-of-Day Checklist

After completing upgrade:
- [ ] All phases executed
- [ ] Tests passing
- [ ] Admin panel tested
- [ ] No errors in logs
- [ ] Performance acceptable
- [ ] Execution log filled
- [ ] Team notified

---

**Print & Keep Nearby During Upgrade**

For full details, refer to:
- `README_UPGRADE.md` - Overview
- `UPGRADE_TECHNICAL_GUIDE.md` - Deep dive
- `UPGRADE_EXECUTION_LOG.md` - Real-time tracking
