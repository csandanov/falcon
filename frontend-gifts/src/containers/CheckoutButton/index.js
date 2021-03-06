import React, { PropTypes } from 'react';
import { Link } from 'react-router-dom';
import { connect } from 'react-redux';
import * as analyticsActions from '../../actions/analytics';

const CheckoutButton = ({ dispatch }) => (
  <Link
    to="/checkout"
    className="btn btn-primary btn-block space-top-none"
    onClick={() => dispatch(analyticsActions.ecommerceCheckout())}
  >
    Checkout
  </Link>
);

CheckoutButton.propTypes = {
  dispatch: PropTypes.func
};

export default connect()(CheckoutButton);
