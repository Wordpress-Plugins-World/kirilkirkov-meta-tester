# Meta Tester WordPress Plugin
A simple and effective WordPress plugin for testing and validating page meta titles and descriptions across multiple URLs.

# Description
Meta Tester helps website administrators and SEO professionals verify that their page titles and meta descriptions match expected values. This is particularly useful for:

- SEO audits and validation
- Quality assurance after site updates
- Bulk meta tag verification
- Content management workflows

# Features
- ✅ Bulk URL Testing - Test multiple URLs at once
- 📊 Visual Results - Clear pass/fail indicators with detailed feedback
- 💾 Data Persistence - Save your test data for repeated use
- 🎯 Precise Matching - Exact string comparison for titles and descriptions
- 🔗 Direct Links - Click URLs in results to open pages in new tabs

# Installation
- Download the plugin file meta-tester.php
- Upload it to your plugins directory
- Activate the plugin through the 'Plugins' menu in WordPress
- Navigate to Meta Tester in your WordPress admin menu

# Usage
Access the Plugin: Go to your WordPress admin dashboard and click on "Meta Tester" in the sidebar menu

Prepare Your Data: Enter your test data in the textarea, one line per URL using this format:

Example Format:
```
https://example.com ||| Home Page Title ||| This is the home page description
```

Save Data: Click "💾 Save" to store your test data for future use
Run Tests: Click "🔍 Check" to validate all URLs against their expected meta values

# Results Interpretation
The plugin will display results in a table format:

- ✅ OK - Both title and description match expected values
- ❌ Title - Title doesn't match (description may be correct)
- ❌ Description - Description doesn't match (title may be correct)
- ❌ Title Description - Both title and description don't match
