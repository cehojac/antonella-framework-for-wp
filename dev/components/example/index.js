const { registerBlockType } = wp.blocks;

import './editor.css';
import './style.css';

registerBlockType('antonella/example',{
    title: 'Example',
    description: 'My First Block with Gutenberg',
    icon: 'smiley',
    category: 'common',
	attributes: {},
    edit: props => {
        return (
			<h1>Hello World !!! from editor</h1>	
        );
    },
    save: props => {
        return (
            <h1>Hello World !!! from Front End</h1>
        );
    }
});