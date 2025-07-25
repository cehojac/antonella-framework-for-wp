import type {SidebarsConfig} from '@docusaurus/plugin-content-docs';

// This runs in Node.js - Don't use client-side code here (browser APIs, JSX...)

/**
 * Creating a sidebar enables you to:
 - create an ordered group of docs
 - render a sidebar for each doc of that group
 - provide next/previous navigation

 The sidebars can be generated from the filesystem, or explicitly defined here.

 Create as many sidebars as you want.
 */
const sidebars: SidebarsConfig = {
  // Sidebar for Antonella Framework
  tutorialSidebar: [
    'intro',
    {
      type: 'category',
      label: '🚀 Getting Started',
      items: [
        'getting-started/introduction',
        'getting-started/installation',
        'getting-started/first-steps',
      ],
    },
    {
      type: 'category',
      label: '🏗️ Architecture',
      items: [
        'architecture/mvc-pattern',
      ],
    },
    {
      type: 'category',
      label: '⚙️ Configuration',
      items: [
        'configuration/overview',
        'configuration/config-overview',
        'configuration/plugin-menu',
        'configuration/custom-post-types',
        'configuration/taxonomies',
        'configuration/hooks-filters',
        'configuration/api-endpoints',
      ],
    },
    {
      type: 'category',
      label: '📖 Guides',
      items: [
        'guides/creating-controllers',
      ],
    },
    {
      type: 'category',
      label: '🔌 Examples',
      items: [
        'examples/simple-crud-example',
        'examples/api-integration-example',
      ],
    },
    {
      type: 'category',
      label: 'Legal',
      items: [
        'legal/aviso-legal', 
        'legal/politica-cookies', 
        'legal/politica-privacidad',
      ],
    },
    {
      type: 'category',
      label: '🚀 Advanced',
      items: [
        'advanced/testing',
        'advanced/deployment',
      ],
    },
    'changelog',
  ],
};

export default sidebars;
