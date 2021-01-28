const { registerBlockType } = wp.blocks;
import { __ } from '@wordpress/i18n';
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, PanelRow, RangeControl } from "@wordpress/components";

// wp.editor.MediaUpload is deprecated. Please use wp.blockEditor.MediaUpload instead.

registerBlockType('antonella/dinamico', {
    title: 'Dynamic Block',
    description: 'Renderiza un block dinámico desde PHP',
    icon: 'media-code',
    attributes: {
        loading: {
            type: 'boolean',
            default: true
        
        },
        posts: {
            type: 'object',
            default: []
        },
        count: {
            type: 'number',
            default: 3
        }
    },
    category: 'antonella-framework',
    edit: props => {

        const { attributes: { posts, count, loading }, className, setAttributes} = props;
        
        console.log( "loading: ", loading );

        //setAttributes({ loading: !loading });

        const foo = async () => {
            if (loading) {
                try {
                    const response = await fetch(`https://carlos-herrera.com/wp-json/wp/v2/posts?_embed&per_page=${count}`, {
                        credentials: 'include',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Basic c2FnaXRhcml1czpzYWdpdGFyaXVz'
                        }
                    });
                    const posts = await response.json();
                    setAttributes({ posts: posts, loading: false });
                } catch (error) { console.log('Authorization failed : ' + error.message); }
            }
        }

        foo();
        
        if ( !posts ) return (
            <h1>Loading...</h1>
        );
        
        if (posts && !posts.length) return(
            <h1>Ho hay Posts</h1>
        );

        return (
			<>
                <InspectorControls>
                    <PanelBody>
                        <PanelRow title="Settings" initialOpen="true">
                            <RangeControl
                                label={ __('Items a mostrar') }
                                value={ count }
                                onChange={ ( count ) => setAttributes( { posts: [], count: count, loading: true}, foo() ) }
                                min={ 3 }
                                max={ 12 } />
                        </PanelRow>
                    </PanelBody>
                </InspectorControls>
                <div class="container">
                    <div class="row">
                        <h1>Últimos post's</h1>
                    </div>
                    <div class="row">
                    {
                        posts.map( post => (
                            <div class="col-sm col-md-4">
                                <img src={post._embedded["wp:featuredmedia"][0].media_details.sizes.full.source_url} 
                                        style={{width: "100%", height: "190px"}} />
                                <h2>{post.title.rendered}</h2>
                            </div>
                        ))
                    }
                    </div>
                </div>
            </>
        );

    },
    save: () => { return null; }
});