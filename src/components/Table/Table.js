import React from 'react';
import { styled } from '@mui/material/styles';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell, { tableCellClasses } from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';


const StyledTableCell = styled(TableCell)(({ theme }) => ({
  [`&.${tableCellClasses.head}`]: {
    backgroundColor: theme.palette.common.black,
    color: theme.palette.common.white,
  },
  [`&.${tableCellClasses.body}`]: {
    fontSize: 14,
  },
}));

const StyledTableRow = styled(TableRow)(({ theme }) => ({
  '&:nth-of-type(odd)': {
    backgroundColor: theme.palette.action.hover,
  },
  // hide last border
  '&:last-child td, &:last-child th': {
    border: 0,
  },
}));

  function createData(RegistrationNumber, ChasisNumber, SalvageDescription, seatingCapacity, vehicleRegistrationNumber, vehicleMakeYear, vehicleBodyType, vehicleCubicCapacity ) {
    return { RegistrationNumber, ChasisNumber, SalvageDescription, seatingCapacity, vehicleRegistrationNumber, vehicleMakeYear, vehicleBodyType, vehicleCubicCapacity };
  }

  const rows = [
    createData(1593432, 63212322, "The car is damaged beyond repair", 4, "GR-4545-2012", "2003", "Salon", "1000m3"),
  ];

  export default function BasicTable() {
    return (
      <TableContainer component={Paper}>
      <Table sx={{ minWidth: 400 }} aria-label="customized table">
        <TableHead>
          <TableRow>
            <StyledTableCell align="right">Registration Number</StyledTableCell>
            <StyledTableCell align="right">Chasis Number</StyledTableCell>
            <StyledTableCell align="right">Salvage Description</StyledTableCell>
            <StyledTableCell align="right">Seating Capacity</StyledTableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {rows.map((row) => (
            <StyledTableRow key={row.name}>
              <StyledTableCell align="right">{row.RegistrationNumber}</StyledTableCell>
              <StyledTableCell align="right">{row.ChasisNumber}</StyledTableCell>
              <StyledTableCell className='font-mono text-wrap text-ellipsis overflow-hidden border-2 border-slate-700' align="right">{row.SalvageDescription}</StyledTableCell>
              <StyledTableCell align="right">{row.seatingCapacity}</StyledTableCell>
            </StyledTableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
    );
  }



