import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
import ListItem from '@mui/material/ListItem';
import Typography from '@mui/material/Typography';
import { blueGrey } from '@mui/material/colors';

function FooterLink(props) {
  const { label, url } = props;

  return (
    <ListItem dense disableGutters>
      <Typography
        component={Link}
        to={url}
        variant="body2"
        sx={{ textDecoration: 'none', color: blueGrey['A100'] }}
      >
        {label}
      </Typography>
    </ListItem>
  );
}

FooterLink.defaultProps = {
  label: null,
  url: null,
};

FooterLink.propTypes = {
  label: PropTypes.string,
  url: PropTypes.string,
};

export default FooterLink;
