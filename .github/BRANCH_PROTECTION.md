# GitHub Branch Protection Setup

This guide explains how to configure GitHub branch protection rules to prevent merging PRs with failing tests.

## Prerequisites

✓ GitHub Actions workflow file (`.github/workflows/tests.yml`) is committed to your repository
✓ You have admin access to the repository

## Setup Instructions

### 1. Navigate to Branch Protection Settings

1. Go to your repository on GitHub
2. Click **Settings** (top navigation)
3. Click **Branches** (left sidebar)
4. Under "Branch protection rules", click **Add rule** (or **Add branch protection rule**)

### 2. Configure Protection Rule

**Branch name pattern:**
```
master
```
(Or `main` if that's your default branch)

**Enable the following settings:**

#### Protect matching branches:
- ✅ **Require a pull request before merging**
  - ✅ Require approvals: `1` (optional, adjust as needed)
  - ✅ Dismiss stale pull request approvals when new commits are pushed

- ✅ **Require status checks to pass before merging**
  - ✅ Require branches to be up to date before merging
  - Search for and select: **Run Tests (PHP 8.4)**
    - This is the job name from your workflow file
    - It will only appear after the workflow has run at least once

- ✅ **Require conversation resolution before merging** (optional but recommended)

- ✅ **Do not allow bypassing the above settings** (recommended)

#### Additional recommended settings:
- ✅ **Require linear history** (keeps git history clean)
- ✅ **Include administrators** (applies rules to admins too)

### 3. Save Changes

Click **Create** or **Save changes** at the bottom of the page.

## How It Works

1. Developer creates a pull request
2. GitHub Actions automatically runs the test workflow
3. The workflow must pass before the PR can be merged
4. If tests fail:
   - The merge button will be disabled
   - A red ❌ appears next to the status check
   - PR cannot be merged until tests pass
5. If tests pass:
   - A green ✓ appears next to the status check
   - PR can be merged (if other requirements are met)

## Testing the Setup

1. Create a test branch:
   ```bash
   git checkout -b test-branch-protection
   ```

2. Make a change that breaks a test:
   ```bash
   # Example: modify a test to fail
   echo "expect(true)->toBeFalse();" >> tests/Unit/ExampleTest.php
   ```

3. Commit and push:
   ```bash
   git add .
   git commit -m "Test: Verify branch protection"
   git push origin test-branch-protection
   ```

4. Create a pull request on GitHub

5. Watch the status check run and fail

6. Verify you cannot merge the PR

7. Clean up:
   ```bash
   git checkout master
   git branch -D test-branch-protection
   git push origin --delete test-branch-protection
   ```

## Workflow Status Badges (Optional)

Add a status badge to your README:

```markdown
[![Tests](https://github.com/YOUR_USERNAME/YOUR_REPO/actions/workflows/tests.yml/badge.svg)](https://github.com/YOUR_USERNAME/YOUR_REPO/actions/workflows/tests.yml)
```

Replace `YOUR_USERNAME` and `YOUR_REPO` with your actual GitHub username and repository name.

## Troubleshooting

### Status check not appearing
- Ensure the workflow has run at least once (push to master or create a PR)
- The status check name must match exactly: `Run Tests (PHP 8.4)`
- Wait a few minutes for GitHub to register the workflow

### Tests passing locally but failing in CI
- Check the workflow logs in the Actions tab
- Common issues:
  - Missing environment variables
  - Database configuration differences
  - PHP version differences
  - Missing dependencies

### Need to force merge in emergency
- Temporarily disable branch protection
- Merge the PR
- Re-enable branch protection immediately
- Fix the failing tests in a follow-up PR

## Additional Resources

- [GitHub Branch Protection Documentation](https://docs.github.com/en/repositories/configuring-branches-and-merges-in-your-repository/managing-protected-branches/about-protected-branches)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
