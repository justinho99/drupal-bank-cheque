# Bank Cheque Generator Module

This module allows you to input necessary fields in a cheque, and converts it to a Cheque format in a nice layout.

## Install

To Install the module, login as an admin user and browse to `{YOUR_CHOICE_OF_DOMAIN}/admin/modules`.
Scroll down to **OTHER** and check the box beside **Bank Cheque Generator** and click the **Install** button.

Once it is installed, you can then browse to `{YOUR_CHOICE_OF_DOMAIN}/bank-cheque-generator`

## Usage

There are four input fields for the form:
- Drawer's First Name
- Drawer's Last Name
- Total Sum of the Payment (in positive integer)
- Payee's Full Name

By input these fields and submitting the form, that will generate the following details for you in a nice Cheque format:
- Current Date
- **Pay** `{Payee's Full Name}`
- **The sum of** `{Total amount in English}`
- **$** `{Total amount in number with commas}`
- `{Drawer Full Name}`
