import React from 'react';
import PropTypes from 'prop-types';
import TextBlock from '../../molecules/TextBlock';

const TextBlockPane = ({ styles, heading, subheading, copy }) => {
  return (
    <div className={"row justify-content-center text-block-pane " + styles}>
      <div className="col-12 col-md-8 col-xl-6">
        <TextBlock heading={heading} subheading={subheading} copy={copy} />
      </div>
    </div>
  );
};

TextBlockPane.propTypes = {
  heading: PropTypes.string,
  subheading: PropTypes.string,
  copy: PropTypes.string,
};

export default TextBlockPane;
