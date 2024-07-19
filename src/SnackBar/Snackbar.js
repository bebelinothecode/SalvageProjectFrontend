import  React from 'react';
import Snackbar from '@mui/material/Snackbar';


function Snackbar(message) {
    const [open, setOpen] = React.useState(false);

  
    const handleClose = (event: React.SyntheticEvent | Event, reason?: string) => {
      if (reason === 'clickaway') {
        return;
      }
      setOpen(false);
    };
  return (
    <div>
        <Snackbar
        open={open}
        autoHideDuration={5000}
        onClose={handleClose}
        message={message}
      />
    </div>
  )
}

export default Snackbar