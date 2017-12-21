import React from 'react';

export default () =>
  <div>
    <h1>Hello from frontend-main/pages/index.js!</h1>
    <style global jsx>{`
      body {
        background-size: cover;
        background: url(${process.env.BASE_URL}/static/image.jpeg);
      }
    `}</style>
  </div>