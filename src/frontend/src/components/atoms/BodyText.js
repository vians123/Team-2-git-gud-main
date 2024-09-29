import PropTypes from 'prop-types';
import Typography from '@mui/material/Typography';

function BodyText(props) {
  const { bold, disableGutter, children, ...rest } = props;

  return (
    <Typography
      component="p"
      variant="body1"
      sx={{ fontWeight: bold ? 700 : 400, mb: disableGutter ? 0 : 2 }}
      {...rest}
    >
      {children}
    </Typography>
  );
}

BodyText.defaultProps = {
  bold: false,
  disableGutter: false,
};

BodyText.propTypes = {
  children: PropTypes.node,
  bold: PropTypes.bool,
  disableGutter: PropTypes.bool,
};

export default BodyText;
