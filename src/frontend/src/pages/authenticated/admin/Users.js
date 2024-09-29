import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { toast } from 'react-toastify';
import { deleteUser, retrieveUser, searchUsers } from 'services/user.service';
import Box from '@mui/material/Box';
import DataTable from 'components/molecules/DataTable';
import AddEditModal from 'components/molecules/users/AddEditModal';
import { criteria, meta as defaultMeta } from 'config/search';

function Users() {
  const { t } = useTranslation();
  const [data, setData] = useState([]);
  const [user, setUser] = useState(null);
  const [query, setQuery] = useState(criteria);
  const [meta, setMeta] = useState(defaultMeta);
  const [open, setOpen] = useState(false);

  const fetchUsers = async () => {
    const { meta, data } = await searchUsers(query);
    setMeta({ ...meta, meta });
    setData(data);
  };

  useEffect(() => {
    fetchUsers();
  }, [query]);

  const headers = [
    {
      id: 'id',
      numeric: false,
      disablePadding: false,
      label: 'ID',
    },
    {
      id: 'first_name',
      numeric: false,
      disablePadding: false,
      label: t('pages.users.first_name'),
    },
    {
      id: 'last_name',
      numeric: false,
      disablePadding: false,
      label: t('pages.users.last_name'),
    },
    {
      id: 'email',
      numeric: false,
      disablePadding: false,
      label: t('pages.users.email_address'),
    },
    {
      id: 'role',
      numeric: false,
      disablePadding: false,
      label: t('pages.users.role'),
    },
    {
      id: 'status.name',
      numeric: false,
      disablePadding: false,
      label: t('pages.users.status'),
    },
  ];

  const handleChangePage = (event, value) => {
    setQuery({ ...query, ...{ page: value } });
  };

  const handleSort = (event, { order, sort }) => {
    setQuery({ ...query, ...{ order, sort } });
  };

  const handleSearch = (keyword) => {
    setQuery({ ...query, ...{ keyword, page: 1 } });
  };

  const handleEdit = async (id) => {
    const user = await retrieveUser(id);
    setOpen(true);
    setUser(user);
  };

  const handleDelete = async (id) => {
    if (confirm(t('pages.users.delete_confirmation'))) {
      await deleteUser(id);
      fetchUsers();
      toast(t('pages.users.user_deleted'), { type: 'success' });
    }
  };

  const handleAdd = () => {
    setUser(null);
    setOpen(true);
  };

  const handleSaveEvent = (response) => {
    if (!user) {
      fetchUsers();
      setOpen(false);
      toast(t('pages.users.user_created'), { type: 'success' });
      return;
    }

    let updatedList = [...data];
    const index = updatedList.findIndex((row) => parseInt(row.id) === parseInt(response.id));
    updatedList[index] = response;
    setData(updatedList);
    setOpen(false);
    toast(t('pages.users.user_updated'), { type: 'success' });
  };

  return (
    <Box>
      <DataTable
        header={headers}
        data={data}
        page={query.page}
        total={meta.lastPage}
        order={query.order}
        sort={query.sort}
        handleChangePage={handleChangePage}
        handleSort={handleSort}
        handleSearch={handleSearch}
        handleEdit={handleEdit}
        handleDelete={handleDelete}
        handleAdd={handleAdd}
      />

      <AddEditModal
        open={open}
        user={user}
        handleSaveEvent={handleSaveEvent}
        handleClose={() => setOpen(false)}
      />
    </Box>
  );
}

export default Users;
