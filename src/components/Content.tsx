import React, { useEffect, useState } from 'react';
import type { TableColumnsType } from 'antd';
import { Button, Col, Drawer, Form, Input, Radio, Row, Space, Table, message } from 'antd';
import axios from 'axios';


interface DataType {
    key: string;
    name: string;
    password: number;
    content: string;
}

const Content: React.FC = () => {
    const [open, setOpen] = useState(false);
    const [form] = Form.useForm();
    const [type, setType] = useState('download')
    const [data, setData] = useState([])

    const showDrawer = () => {
        setOpen(true);
    };

    const onClose = () => {
        setOpen(false);
    };

    useEffect(() => {
        axios.get('https://quanyp.wpcomstaging.com/wp-json/your-plugin/v1/content')
            .then(data => setData(data.data.map((dt: any) => ({
                id: dt.id,
                name: dt.name,
                password: dt.password,
                content: dt.url ? dt.url : dt.content_short
            }))))
    }, [])

    const columns: TableColumnsType<DataType> = [
        {
            title: 'Tên',
            dataIndex: 'name',
            key: 'name',
            width: '30%',
        },
        {
            title: 'Mật khẩu',
            dataIndex: 'password',
            key: 'age',
            width: '20%',
        },
        {
            title: 'Nội dung',
            dataIndex: 'content',
            key: 'content',
        },
    ];
    const onFinish = (values: any) => {
        axios.post('https://quanyp.wpcomstaging.com/wp-json/your-plugin/v1/content', {
            url: null,
            name: null,
            password: null,
            type: null,
            content_short: null,
            content_long: null,
            ...values
        })
            .then(data => {
                console.log(data.data)
                message.success('Tạo thành công')
                onClose()
            })
            .catch(err => console.log(err))
        console.log('Form values:', values);
    };
    return (
        <div style={{
            padding: 8
        }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', padding: '8px 0' }}>
                <div>sdf</div>
                <Button type="primary" onClick={showDrawer}>
                    Tạo mới
                </Button>
            </div>
            <Table style={{ width: '100%', height: '100%' }} columns={columns} dataSource={data} />
            <Drawer size='large'
                title="Tạo nội dung mới" onClose={onClose} open={open} extra={<Space>
                    <Button onClick={() => form.submit()} type="primary">
                        Tạo
                    </Button>
                </Space>} >
                <Form layout="vertical" form={form} onFinish={onFinish} initialValues={{ type }}>
                    <Row gutter={16}>
                        <Col span={12}>
                            <Form.Item
                                name="name"
                                label="Nội dung"
                                rules={[{ required: true, message: 'Please enter user name' }]}
                            >
                                <Input placeholder="Please enter user name" />
                            </Form.Item>
                        </Col>
                        <Col span={12}>
                            <Form.Item
                                name="password"
                                label="Mật khẩu"
                                rules={[{ required: true }]}
                            >
                                <Input placeholder='Nhập mật khẩu' />
                            </Form.Item>
                        </Col>
                        <Col span={12}>
                            <Form.Item
                                name="type"
                                label="Kiểu"
                                rules={[{ required: true }]}
                            >
                                <Radio.Group onChange={(e: any) => setType(e.target.value)} value={type}>
                                    <Radio value="download"> Dowload </Radio>
                                    <Radio value="content"> Nội dung </Radio>
                                </Radio.Group>
                            </Form.Item>
                        </Col>
                    </Row>
                    {type == "download" ?
                        <Row gutter={16}>
                            <Col span={24}>
                                <Form.Item
                                    name="url"
                                    label="Liên kết"
                                    rules={[
                                        {
                                            required: true,
                                            message: 'please enter url description',
                                        },
                                    ]}
                                >
                                    <Input />
                                </Form.Item>
                            </Col>
                        </Row> :
                        <Row gutter={16}>
                            <Col span={24}>
                                <Form.Item
                                    name="content_short"
                                    label="Nội dung ngắn"
                                    rules={[
                                        {
                                            required: true,
                                            message: 'please enter url description',
                                        },
                                    ]}
                                >
                                    <Input.TextArea rows={4} placeholder="please enter url description" />
                                </Form.Item>
                            </Col>
                            <Col span={24}>
                                <Form.Item
                                    name="content_long"
                                    label="Nội dung dài"
                                    rules={[
                                        {
                                            required: true,
                                            message: 'please enter url description',
                                        },
                                    ]}
                                >
                                    <Input.TextArea rows={4} placeholder="please enter url description" />
                                </Form.Item>
                            </Col>
                        </Row>
                    }
                </Form>
            </Drawer>
        </div >
    )

};

export default Content;