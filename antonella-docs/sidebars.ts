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
      label: 'Configuration',
      items: [
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
      label: 'Antonella Framework Guide',
      items: [
        'tutorial-basics/create-a-document',
        'tutorial-basics/create-a-blog-post',
        'tutorial-basics/create-a-page',
        'tutorial-basics/advanced-features',
      ],
    },
    {
      type: 'category',
      label: 'Tutorial Extras',
      items: [
        'tutorial-extras/manage-docs-versions',
        'tutorial-extras/translate-your-site',
      ],
    },
  ],
};

export default sidebars;
