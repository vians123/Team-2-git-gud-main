// metrics in "rem" not "px"
const pxToRem = (val) => `${val / 16}rem`;

/**
 * MUI Typography
 * @see https://mui.com/material-ui/customization/typography/
 */
const typography = {
  fontFamily: '"Noto Sans JP", sans-serif',
  h1: {
    fontSize: pxToRem(48),
    fontWeight: 700,
  },
  h2: {
    fontSize: pxToRem(42),
    fontWeight: 700,
  },
  h3: {
    fontSize: pxToRem(36),
    fontWeight: 700,
  },
  h4: {
    fontSize: pxToRem(30),
    fontWeight: 700,
  },
  h5: {
    fontSize: pxToRem(24),
    fontWeight: 700,
  },
  h6: {
    fontSize: pxToRem(18),
    fontWeight: 700,
  },
  subtitle1: {
    fontSize: pxToRem(14),
    fontWeight: 300,
  },
  subtitle2: {
    fontSize: pxToRem(12),
    fontWeight: 300,
  },
  body1: {
    fontSize: pxToRem(14),
    fontWeight: 400,
  },
  body2: {
    fontSize: pxToRem(12),
    fontWeight: 400,
  },
  button: {
    fontSize: pxToRem(14),
    fontWeight: 400,
  },
  caption: {
    fontSize: pxToRem(12),
    fontWeight: 300,
  },
  overline: {
    fontSize: pxToRem(10),
    fontWeight: 300,
  },
};

export default typography;
