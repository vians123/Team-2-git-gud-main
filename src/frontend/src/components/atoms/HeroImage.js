import PropTypes from 'prop-types';
import Paper from '@mui/material/Paper';

function HeroImage(props) {
  const { image, children, height, ...rest } = props;

  const styles = {
    backgroundImage: `url(${image})`,
    backgroundPosition: 'center',
    height: height,
    borderRadius: 0,
    backgroundRepeat: 'none',
    backgroundSize: 'cover',
  };

  return (
    <Paper elevation={0} style={styles} {...rest}>
      {children}
    </Paper>
  );
}

HeroImage.defaultProps = {
  image: null,
  height: '100vh',
};

HeroImage.propTypes = {
  image: PropTypes.string,
  children: PropTypes.node,
  height: PropTypes.string,
};

export default HeroImage;
