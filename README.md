
# GLPI Satisfaction - Satisfaction Survey Plugin

This plugin for **GLPI** lets users answer the satisfaction survey directly from an email, without needing to log in to the system. It simplifies feedback collection and improves the user experience.

## Features

- Automatic satisfaction surveys sent via email.
- Responses can be submitted with a single click, directly from the email body.
- The recipient does not need to be logged in to GLPI to answer the survey.

## Requirements

- GLPI version 10.0 or higher.

## Installation

1. Download or clone the plugin repository on your GLPI server:
   ```bash
   git clone https://github.com/isaque-silva/satisfacao.git
   ```

2. Move the plugin folder into the GLPI plugins directory:
   ```bash
   mv satisfacao /your-path/glpi/plugins/
   ```

3. In the GLPI admin panel, go to **Setup > Plugins** and activate the satisfaction plugin.

## Configuration

After activating the plugin, configure the satisfaction survey as follows:

1. In the satisfaction survey email, use the tag:
   ```
   ##ticket.satisfacao##
   ```

2. When the email is sent, the user will receive the following images with links to answer the survey directly:

   ![image](https://github.com/user-attachments/assets/69e4ac8c-6b97-48c2-a27a-f88e81ffcc56)

   The images represent the response options (satisfied, dissatisfied, etc.) with personalized links for submitting the response.

## How It Works

1. When a ticket is resolved or closed, GLPI automatically sends a satisfaction survey email to the requester.
2. The email contains a set of images with links so the user can choose their satisfaction level with just one click.
3. After the choice, the response is recorded directly in GLPI, with no need to log in.

## Contributing

Contributions are welcome! Feel free to open a **pull request** or report a problem on the [issues page](https://github.com/isaque-silva/satisfacao/issues).
