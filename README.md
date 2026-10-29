# OneListing — Featured checkout fix

A patched build of the **OneListing 2.0.24** WordPress theme by [wpWax](https://wpwax.com/), prepared for the standalone Directorist Featured listing checkout issue.

This repository is a personal distribution of the tested fix. It is not an official wpWax release. Original author credits and bundled notices are preserved.

## What changed

OneListing rendered the General/Featured controls before the listing submission form and excluded them from the normal field sections. The selected `listing_type` was consequently omitted from form submission, allowing a Featured selection to be processed as a General listing.

The patch removes the before-form renderer and renders the controls through the normal form sections. The selection now belongs to the submitted form and appears once.

Changed theme files:

- `inc/directorist-support.php`
- `directorist/listing-form/section.php`

## Download and install

1. Download **onelisting-2.0.24-featured-checkout-fix.1.zip** from [Releases](https://github.com/the-sohan/onelisting-featured-checkout-fix/releases/latest).
2. Back up your existing theme and any customizations.
3. In WordPress, open **Appearance → Themes → Add New → Upload Theme** and upload the ZIP. Replace the installed OneListing theme when prompted.
4. Use the Directorist Add Listing page to test a Featured submission.

The ZIP contains the `onelisting/` theme directory and preserves the original theme version header (`2.0.24`). The release tag identifies this patched build. A subsequent theme update can replace these changes.

## Directorist configuration

This fix was tested with **Pricing Plans disabled**, using standalone Featured monetization. Enable monetization and Featured listings, set the Featured price, and check the designated pages in Directorist settings:

- Checkout: a published page containing `[directorist_checkout]`.
- Payment Receipt: a published page containing `[directorist_payment_receipt]`.

Theme installation does not change these site settings. An incorrect Checkout assignment can independently prevent the payment page from loading.

## Verification

Tested locally with Directorist 8.10.5, WordPress 7.1.3, and PHP 8.2.29.

| Active theme | Submission | Preview | Result |
| --- | --- | --- | --- |
| Patched OneListing 2.0.24 | Featured | ON | Continue to Publish reaches checkout; listing remains pending before payment |
| Patched OneListing 2.0.24 | Featured | OFF | Direct checkout; listing remains pending before payment |
| Patched OneListing 2.0.24 | General | OFF | Normal publication without checkout |
| Twenty Twenty-Five 1.5 | Featured | ON | Continue to Publish reaches checkout; listing remains pending before payment |
| Twenty Twenty-Five 1.5 | Featured | OFF | Direct checkout; listing remains pending before payment |
| Twenty Twenty-Five 1.5 | General | OFF | Normal publication without checkout |

Six local HTTP form-submission checks passed, with two additional complete browser flows under the two themes. Featured checkout displayed the configured $10 total and Bank Transfer option. Desktop and mobile form rendering was inspected. The two changed PHP files passed syntax checks.

Payment was not submitted. Payment completion, paid receipt rendering, and the Pricing Plans flow are outside this verification.

Related report: [Featured listing skips checkout with OneListing theme](https://wordpress.org/support/topic/featured-listing-skips-checkout-with-onelisting-theme-works-with-twenty-twenty/).
