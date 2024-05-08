import React from 'react';
import { ColorRing } from 'react-loader-spinner';

const Loading = () => {
  return (
    <div
      style={{
        height: '100vh',
        width: '100%',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
      }}
    >
      <ColorRing
        visible={true}
        height="80"
        width="80"
        ariaLabel="color-ring-loading"
        wrapperStyle={{}}
        wrapperClass="color-ring-wrapper"
        colors={['#000000', '#000000', '#000000', '#000000', '#000000']}
      />
    </div>
  );
};

export default Loading;
