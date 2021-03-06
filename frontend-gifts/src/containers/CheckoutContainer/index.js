import React from 'react';
import * as regionUtils from '../../utils/region';
import CheckoutContainerGB from './gb/CheckoutContainer';
import CheckoutContainerIE from './ie/CheckoutContainer';

const CheckoutContainer = () => (
  regionUtils.isRegionGB() ? <CheckoutContainerGB /> : <CheckoutContainerIE />
);

export default CheckoutContainer;
