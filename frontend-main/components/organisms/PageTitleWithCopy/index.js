import React from 'react';
import Heading1WithSubheadingAndCopy from '../../molecules/Heading1WithSubheadingAndCopy';

export default (props) =>
  <div className={"row justify-content-center pagetitle-with-copy " + props.styles}>
    <div className="col-6">
      <Heading1WithSubheadingAndCopy/>
    </div>
  </div>

