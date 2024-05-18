# DNS Control Panel

This control panel is designed to update zones via AXFR ([RFC 5936](https://datatracker.ietf.org/doc/html/rfc5936)) and nsupdate ([RFC 2136](<(https://datatracker.ietf.org/doc/html/rfc2136)>)).

## Getting started

### Requirement

- PHP (only tested with 8+, 7.4+ probably also works, please send feedback)
- PHP function `popen` enabled
- dig
- nsupdate

### Installation

- Copy all files to your web server
- Go to `config` and create a file `config.php`
- Copy the content from `config.php.sample`
- Fill all fields (see `default.php` for all possible fields)
- Ready

### Hints

For creating the password hash you can use an online tool like https://bcrypt.online/. Please be aware that you are sending your password to a third-party service.

## Contribution

I created this project primarily for myself. If anyone has feedback, suggestions for improvement or even a pull request, I would be very pleased.
