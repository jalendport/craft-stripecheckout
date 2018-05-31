# Stripe Checkout Changelog

All notable changes to this project will be documented in this file.

## 2.0.1 - 2018-05-31

### Fixed

- Migration table schema

## 2.0.0 - 2018-05-31

### Added

- Default templates
- The ability to filter, search, sort and delete charges
- The ability to override the plugin name
- View raw charge data
- Additional parameters can be inserted into the stripe charge request via the `BEFORE_CHARGE` event

### Changed

- Updated Stripe to `^6.7`
- Charges are now elements
- Improved error handling
- Improved settings templates
- Event names changed to `EVENT_BEFORE_CHARGE` and `EVENT_AFTER_CHARGE`
- Updated icons
- More charge data made available
