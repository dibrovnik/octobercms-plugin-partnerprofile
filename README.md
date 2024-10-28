# PartnerProfile Plugin for October CMS

The **PartnerProfile** plugin provides robust functionality for managing partner profiles in October CMS. This plugin is designed to handle various partner-related data, such as dynamic fields, taxonomies, pricing, and portfolio images, making it ideal for websites that require flexible partner or service provider profiles.

## Features

- **Partner Profile Management**: Create and manage detailed profiles for partners or service providers.
- **Dynamic Fields**: Customize partner profiles with various dynamic fields and store personalized data.
- **Taxonomies**: Organize and categorize partners using taxonomy options, with easy-to-use filtering.
- **Portfolio Management**: Add and manage partner portfolios with both vertical and horizontal image support.
- **Pricing Options**: Set up and display multiple pricing options for each partner.
- **Authentication Components**: Seamlessly integrate authentication components with October CMS’s `RainLab.User` plugin.

## Installation

To install this plugin in your October CMS project, you can either install via the console or manually clone it:

### Install via October CMS Console
Run the following command to install the plugin through October CMS’s artisan command:

```bash
php artisan plugin:install YourVendor.PartnerProfile
```

### Manual Installation via GitHub
Clone this repository directly into the `plugins` directory of your October CMS project:

```bash
cd plugins
git clone https://github.com/YOUR_GITHUB_USERNAME/octobercms-plugin-partnerprofile.git yourvendor/partnerprofile
```

Then, run the following command to apply the plugin’s database migrations:

```bash
php artisan october:up
```

## Usage

1. **Add Components**: Use the available components (`AuthComponent`, `Cards`, `DetailPage`, `PartnerProfileForm`, `Taxonomies`) in your pages to manage partner data and display profile details.
2. **Template Integration**: Call specific methods in your templates to access dynamic field values or display partner data. Example:

    ```html
    <p>{{ partnerProfileForm.fieldValue('tel') }}</p>
    ```

3. **Customize Partner Profiles**: Access the backend to create, edit, and categorize partner profiles with taxonomies, field values, and pricing.

## Contributing

Contributions are welcome! Please fork this repository, make your changes, and submit a pull request. For major changes, please open an issue first to discuss what you would like to change.

---

This description covers the primary functionality, installation steps, and usage instructions for potential users or developers.
