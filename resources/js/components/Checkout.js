import React, { Component, useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { Modal, Button, InputGroup, FormControl, Container, Col, Table, Row } from 'react-bootstrap';

function CartItemRow(props) {
  return <tr>
    <td className="text-center">{ props.item.id }</td>
    <td>{ props.item.description }</td>
    <td className="text-right">{ props.item.price }</td>
  </tr>
}

function Checkout() {
  const [cartData, setCartData] = useState({items: [], total: 0});
  const [couponName, setCouponName] = useState('');
  const [modalShow, setModalShow] = useState(false);
  const [modalBody, setModalBody] = useState('');

  useEffect(() => {
    axios.get('/api/cart').then((response) => {
      setCartData(response.data.data)
    })
  }, []);

  const openModal = (bodyText) => {
    setModalBody(bodyText)
    setModalShow(true)
  }

  const redeemCoupon = (event) => {
    axios.put(`/api/cart/coupon/${couponName}`).then((response) => {
      setCartData(response.data.data)
     }).catch((error) => {
      if (error.response && error.response.status === 404) {
        return openModal(`The discount code '${couponName}' is not valid.`)
      }
      if (error.response && error.response.status === 422) {
        return openModal(`Discount code '${couponName}' is not applicable on your cart.`)
      }

      return openModal("Sorry, can't add your discount code at the moment" )
    })
  }

  return (
    <Container>
      <h1 className="pt-4 text-center text-muted">Shopping cart</h1>
      <Row>
        <Col></Col>
        <Col xs={9}>
          <Table striped bordered hover className="mt-5">
            <thead>
              <tr>
                <th className="text-center">Item #</th>
                <th>Description</th>
                <th className="text-center">Price</th>
              </tr>
            </thead>
            <tbody>
              { cartData.items.map((i) => <CartItemRow item={i} key={i.id} />) }
            </tbody>
          </Table>
          { cartData.coupon && <>
            <p className="text-center bg-light text-info lead">Coupon applied: {cartData.coupon.name}</p>
            <p className="text-right pr-2 text-information my-0"><del>Total ${cartData.coupon.original_total}</del></p>
          </>}
          <p className="text-right pr-2 text-information bg-warning font-weight-bold">Total ${cartData.total}</p>
          <InputGroup className="mb-3">
            <FormControl placeholder="Discount Code" onChange={(e) => setCouponName(e.target.value.trim().toUpperCase())} value={couponName} />
            <InputGroup.Append>
              <Button onClick={redeemCoupon} variant="outline-secondary" disabled={!couponName}>Apply</Button>
            </InputGroup.Append>
          </InputGroup>
        </Col>
        <Col></Col>
      </Row>
      <Modal show={modalShow} onHide={() => setModalShow(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Coupon Code</Modal.Title>
        </Modal.Header>
        <Modal.Body>{ modalBody }</Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setModalShow(false)}>Ok</Button>
        </Modal.Footer>
      </Modal>
    </Container>
  );
}

ReactDOM.render(<Checkout />, document.getElementById('checkout'));
