import PropTypes from 'prop-types';
import { useTranslation } from 'react-i18next';
import DeleteIcon from '@mui/icons-material/Delete';
import EditIcon from '@mui/icons-material/Edit';
import { TableBody as MuiTableBody } from '@mui/material';
import IconButton from '@mui/material/IconButton';
import TableCell from '@mui/material/TableCell';
import TableRow from '@mui/material/TableRow';

function TableBody(props) {
  const { header, rows, handleDelete, handleEdit, actions } = props;
  const { t } = useTranslation();

  return (
    <MuiTableBody>
      {rows.map((row, index) => {
        return (
          <TableRow hover role="checkbox" tabIndex={-1} key={index}>
            {header.map((cell, index) => {
              const getLabel = (cell) => {
                let label = row;
                // support value from objects e.g cell id: "status.name"  |  { "status": { "name": "Active"} }
                cell.id.split('.').forEach((key) => {
                  label = label[key];
                });
                return label;
              };

              return (
                <TableCell key={index} align={cell.numeric ? 'right' : 'left'}>
                  {getLabel(cell)}
                </TableCell>
              );
            })}
            {actions && (
              <TableCell align="right">
                <IconButton onClick={() => handleDelete(row.id)}>
                  <DeleteIcon sx={{ fontSize: '1rem' }} />
                </IconButton>

                <IconButton onClick={() => handleEdit(row.id)}>
                  <EditIcon sx={{ fontSize: '1rem' }} />
                </IconButton>
              </TableCell>
            )}
          </TableRow>
        );
      })}

      {rows.length < 1 && (
        <TableRow>
          <TableCell align="center" colSpan={6}>
            {t('table.no_data')}
          </TableCell>
        </TableRow>
      )}
    </MuiTableBody>
  );
}

TableBody.defaultProps = {
  header: [],
  rows: [],
  actions: true,
  handleDelete: (id) => alert(`Delete id # ${id}`),
  handleEdit: (id) => alert(`Edit id # ${id}`),
};

TableBody.propTypes = {
  header: PropTypes.array,
  rows: PropTypes.array,
  handleDelete: PropTypes.func,
  handleEdit: PropTypes.func,
  actions: PropTypes.bool,
};

export default TableBody;
