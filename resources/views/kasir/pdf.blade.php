<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran - {{ $transaction->invoice_number }}</title>
    <style>
        /* Mengatur ukuran kertas kecil (thermal printer style) */
        @page { size: 80mm 200mm; margin: 2mm; }
        
        body { 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 12px; 
            line-height: 1.2; 
            color: #000; 
            margin: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { 
            margin-bottom: 15px; 
            border-bottom: 1px dashed #000; 
            padding-bottom: 10px; 
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { border-bottom: 1px dashed #000; padding: 5px 0; text-align: left; }
        td { padding: 3px 0; vertical-align: top; }
        
        .total-section { 
            border-top: 1px dashed #000; 
            padding-top: 10px; 
            margin-top: 10px; 
        }
        .footer { 
            margin-top: 20px; 
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .info-row { font-size: 10px; margin: 2px 0; }
    </style>
</head>
<body>
    <div class="text-center header">
        <h2 style="margin:0; text-transform: uppercase; font-size: 16px;">KASIR SUPERMARKET</h2>
        <p style="margin:2px 0;">Jl. Raya Pajajaran, Bogor</p>
        <div class="info-row">
            <span>No: {{ $transaction->invoice_number }}</span><br>
            <span>Kasir: {{ Auth::user()->name ?? 'Admin' }}</span><br>
            <span>Waktu: {{ $transaction->created_at->translatedFormat('d F Y H:i:s') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $detail)
            <tr>
                <td>{{ $detail->product_name }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <td><strong>GRAND TOTAL</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Tunai</td>
                <td class="text-right">Rp {{ number_format($transaction->cash_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="text-right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="text-center footer">
        <p style="margin: 0; font-weight: bold;">TERIMA KASIH</p>
        <p style="margin: 5px 0 0 0; font-size: 10px;">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>
</body>
</html>