import {themes as prismThemes} from 'prism-react-renderer';
import type {Config} from '@docusaurus/types';
import type * as Preset from '@docusaurus/preset-classic';

// This runs in Node.js - Don't use client-side code here (browser APIs, JSX...)

const config: Config = {
  title: 'Antonella Framework v1.9',
  tagline: 'A powerful WordPress framework for developers - Version 1.9',
  favicon: 'img/favicon.ico',

  // Future flags, see https://docusaurus.io/docs/api/docusaurus-config#future
  future: {
    v4: true, // Improve compatibility with the upcoming Docusaurus v4
  },

  // Set the production url of your site here
  url: 'https://antonellaframweork.com',
  // Set the /<baseUrl>/ pathname under which your site is served
  // For GitHub pages deployment, it is often '/<projectName>/'
  baseUrl: '/antonella-framework-for-wp/',

  // GitHub pages deployment config.
  // If you aren't using GitHub pages, you don't need these.
  organizationName: 'cehojac', // Usually your GitHub org/user name.
  projectName: 'antonella-framework-for-wp', // Usually your repo name.

  onBrokenLinks: 'throw',
  onBrokenMarkdownLinks: 'warn',

  // Even if you don't use internationalization, you can use this field to set
  // useful metadata like html lang. For example, if your site is Chinese, you
  // may want to replace "en" with "zh-Hans".
  i18n: {
    defaultLocale: 'es',
    locales: ['es'],
    localeConfigs: {
      es: {
        label: 'Español',
        direction: 'ltr',
        htmlLang: 'es-ES',
        calendar: 'gregory',
        path: 'es',
      },
    },
  },

  presets: [
    [
      'classic',
      {
        docs: {
          sidebarPath: './sidebars.ts',
          // Please change this to your repo.
          // Remove this to remove the "edit this page" links.
          editUrl:
            'https://github.com/facebook/docusaurus/tree/main/packages/create-docusaurus/templates/shared/',
        },
        blog: {
          showReadingTime: true,
          feedOptions: {
            type: ['rss', 'atom'],
            xslt: true,
          },
          // Please change this to your repo.
          // Remove this to remove the "edit this page" links.
          editUrl:
            'https://github.com/facebook/docusaurus/tree/main/packages/create-docusaurus/templates/shared/',
          // Useful options to enforce blogging best practices
          onInlineTags: 'warn',
          onInlineAuthors: 'warn',
          onUntruncatedBlogPosts: 'warn',
        },
        theme: {
          customCss: './src/css/custom.css',
        },
      } satisfies Preset.Options,
    ],
  ],

  themeConfig: {
    // Replace with your project's social card
    image: 'img/docusaurus-social-card.jpg',
    colorMode: {
      defaultMode: 'light',
      disableSwitch: false,
      respectPrefersColorScheme: false,
    },
    navbar: {
      title: 'Antonella Framework',
      logo: {
        alt: 'Antonella Framework Logo',
        src: 'https://antonellaframework.com/wp-content/uploads/2017/01/cropped-logo-antonella-framework-512_2.png',
      },
      items: [
        {
          type: 'docSidebar',
          sidebarId: 'tutorialSidebar',
          position: 'left',
          label: 'Documentation',
        },
        {to: '/blog', label: 'Blog', position: 'left'},
        {
          type: 'localeDropdown',
          position: 'right',
        },
        {
          href: 'https://github.com/cehojac/antonella-framework-for-wp',
          label: 'GitHub',
          position: 'right',
        },
      ],
    },
    footer: {
      style: 'light',
      links: [
        {
          title: 'Documentation',
          items: [
            {
              label: 'Getting Started',
              to: '/docs/intro',
            },
            {
              label: 'Architecture',
              to: '/docs/architecture/mvc-pattern',
            },
            {
              label: 'Examples',
              to: '/docs/examples/simple-crud-example',
            },
          ],
        },
        {
          title: 'Community',
          items: [
            {
              label: 'Website',
              href: 'https://antonellaframework.com',
            },
            {
              label: 'Support',
              href: 'https://github.com/cehojac/antonella-framework-for-wp/issues',
            },
            {
              label: 'Discussions',
              href: 'https://github.com/cehojac/antonella-framework-for-wp/discussions',
            },
          ],
        },
        {
          title: 'Resources',
          items: [
            {
              label: 'Blog',
              to: '/blog',
            },
            {
              label: 'GitHub',
              href: 'https://github.com/cehojac/antonella-framework-for-wp',
            },
            {
              label: 'Releases',
              href: 'https://github.com/cehojac/antonella-framework-for-wp/releases',
            },
          ],
        },
        {
          title: 'Legal',
          items: [
            {
              label: 'Aviso Legal',
              to: '/docs/legal/aviso-legal',
            },
            {
              label: 'Política de Cookies',
              to: '/docs/legal/politica-cookies',
            },
            {
              label: 'Política de Privacidad',
              to: '/docs/legal/politica-privacidad',
            },
          ],
        },
      ],
      copyright: `
        <div>
          <div>Copyright © ${new Date().getFullYear()} Antonella Framework. Todos los derechos reservados.</div>
          <div class="footer__creator">Creado por <a href="https://carlos-herrera.com" target="_blank" rel="noopener noreferrer">Carlos Herrera</a></div>
        </div>
      `,
    },
    prism: {
      theme: prismThemes.github,
      darkTheme: prismThemes.dracula,
    },
  } satisfies Preset.ThemeConfig,
};

export default config;
