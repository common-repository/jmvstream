/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

const fontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif';
const styles = {
  blockDiv: {
    padding: '1em',
    minHeight: '200px',
    boxShadow: 'inset 0 0 0 1px #1e1e1e',
  },
  blockTitle: {
    fontFamily: fontFamily,
    fontSize: '18pt',
    fontWeight: '400',
    textAlign: 'left',
    marginBottom: '16px',
  },
  blockSubtitle: {
    fontFamily: fontFamily,
    fontSize: '13px',
    fontWeight: '400',
    textAlign: 'left',
    marginBottom: '1em',
  },
  blockInput: {
    fontFamily: fontFamily,
    width: '100%',
    padding: '8px',
    fontSize: '14px',
  },
  blockMediaButton: {
    fontFamily: fontFamily,
    fontSize: '13px',
    padding: '6px',
    color: '#007cba',
    cursor: 'pointer',
  }
}

function randomId() {
  return Math.floor(Math.random() * 1000000);
}

wp.blocks.registerBlockType('media/jmvstream', {
  title: `${jmvstream.translations.jmvstream_videos}`,
  icon: 'smiley',
  category: 'media',
  attributes: {
    content: { type: 'string' },
    blockId: { type: 'string' },
  },

  edit: function (props) {

    const { attributes, setAttributes } = props;
    const { blockId } = attributes;

    React.useEffect(() => {
      if (!blockId) {
        setAttributes({ blockId: randomId() });
      }
    }, []);

    function updateContent(event) {
      
      props.setAttributes({ content: event.target.value });
    }

    return React.createElement("div", { style: { padding: '1em', minHeight: '200px', boxShadow: 'inset 0 0 0 1px #1e1e1e' } },
      React.createElement(
        "h3",
        { style: styles.blockTitle },
        `${jmvstream.translations.jmvstream_videos}`
      ),
      React.createElement("p", { style: styles.blockSubtitle }, `${jmvstream.translations.gutenberg_label}`),
      React.createElement("input", { type: "text", value: props.attributes.content, onChange: updateContent, style: styles.blockInput, id: "jmvstream__" + blockId }),
      React.createElement("a", { style: styles.blockMediaButton, onClick: () => openGutenbergModal(blockId) }, `${jmvstream.translations.jmvstream_videos}`),
    );
  },

  save: function (props) {
    if (!props.attributes.content) {
      return wp.element.createElement(
        "div",
        null,
        React.createElement(
          "p",
          null,
         `${jmvstream.translations.gutenberg_error}`
        ));
    }
    else if (props.attributes.content.includes("[jmvstream")) {
      return wp.element.createElement(
        "div",
        null,
        props.attributes.content
      );
    } else if (props.attributes.content.includes("player.jmvstream.com")) {
      return wp.element.createElement(
        "div",
        null,
        React.createElement(
          "iframe",
          {
            src: props.attributes.content,
          }
        )
      );
    } else {
      return wp.element.createElement(
        "div",
        null,
        React.createElement(
          "p",
          null,
         `${jmvstream.translations.gutenberg_error}`
        ));
    }

  }
});