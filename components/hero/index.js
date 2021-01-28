const { registerBlockType } = wp.blocks;
const { RichText, MediaUpload } = wp.blockEditor;
const { Button } = wp.components;

// wp.editor.MediaUpload is deprecated. Please use wp.blockEditor.MediaUpload instead.

import './editor.css';
import './css/style.css';

registerBlockType('antonella/hero',{
    title: 'Hero Block',
    description: 'Añade una imagen de fondo con texto centrado verticalmente',
    icon: 'cover-image',
    category: 'antonella-framework',
    attributes: {
        title: {
            type: 'string',
            source: 'html',
            selector: '.hero-section .hero-section-text h1',
            default: 'My First Block'
        },
        subtitle: {
            type: 'string',
            source: 'html',
            selector: '.hero-section .hero-section-text h5',
            default: '<a href="https://antonellaframework.com/documentacion/">@antonella-framework</a>'
        },
        image: {
            type: 'string',
            selector: '.hero-section .hero-section-text',
            default: 'https://i.picsum.photos/id/171/1200/300.jpg?grayscale&hmac=DD7fhKeQ5gNrjsUgrhFmLT7HuYX2Bc8ZtrbM8fvlU2M'
        }
    },
    supports: {
        align: ['wide', 'full']
    },
    edit: props => {
        // extraemos los valores del props
        const { attributes: { title, subtitle, image }, setAttributes } = props;

        // accede al title
        const onChangeTitle = newTitle => { setAttributes({ title: newTitle }); }

        // accede al subtitle
        const onChangeSubtitle = newSubTile => { setAttributes({ subtitle: newSubTile }); }

        // accede a la imagen seleccionada
        const onSelectImagen = newImagen => { setAttributes({ image: newImagen.sizes.full.url }); }

        return (
            <div className="hero-section" style={{backgroundImage: `url(${image})`}}>
                
                {/* https://github.com/WordPress/gutenberg/blob/master/packages/block-editor/src/components/media-upload/README.md */}
                <MediaUpload 
                    onSelect={onSelectImagen}
                    type="image"
                    value={image}
                    render={({open}) => <Button onClick={open} icon='format-image' showToolTip="true" label="Cambiar Imagen" />}
                />
                
                <div className="hero-section-text">
                    <h1>
                        <RichText
                            placeholder="Introduzca un title"
                            value={title}
                            onChange={onChangeTitle} />
                    </h1>
                    <h5>
                        <RichText 
                            placeholder="Introduzca un subtitle" 
                            value={subtitle} 
                            onChange={onChangeSubtitle} />
                    </h5>    
                </div>

            </div>
        );
    },
    save: props => {
        const { attributes: { title, subtitle, image } } = props;
        return (
            <div className="hero-section" style={{backgroundImage: `url(${image})`}}>
                <div className="hero-section-text">
                    <h1><RichText.Content value={title} /></h1>
                    <h5><RichText.Content value={subtitle} /></h5>
                </div>
            </div>
        );
    }
});