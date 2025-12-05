import { Dimensions } from 'react-native';

// export const { width, height } = Dimensions.get('window');

const MAX_HEIGHT = 1400;
  const MAX_WIDTH = 1000;
  export const {width, height} = Dimensions.get('window')


  const effectiveWidth = Math.min(width, MAX_WIDTH);
  const effectiveHeight = Math.min(height, MAX_HEIGHT);

  export { effectiveHeight, effectiveWidth };

